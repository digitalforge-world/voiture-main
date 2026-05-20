<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $groqKey;
    protected $geminiKey;

    public function __construct()
    {
        $this->groqKey = env('GROQ_API_KEY');
        $this->geminiKey = env('GEMINI_API_KEY');
    }

    /**
     * Get the next AI response given the message history.
     *
     * @param array $messages History of messages [ ['role' => 'user|assistant', 'content' => '...'] ]
     * @return array [ 'message' => '...', 'metadata' => [...] ]
     */
    public function getResponse(array $messages): array
    {
        $systemPrompt = $this->getSystemPrompt();

        // 1. Try Groq first if key is present
        if ($this->groqKey) {
            try {
                $response = $this->callGroq($systemPrompt, $messages);
                if ($response) {
                    return $response;
                }
            } catch (\Exception $e) {
                Log::error("Groq AI Service Error: " . $e->getMessage());
            }
        }

        // 2. Try Gemini next if key is present
        if ($this->geminiKey) {
            try {
                $response = $this->callGemini($systemPrompt, $messages);
                if ($response) {
                    return $response;
                }
            } catch (\Exception $e) {
                Log::error("Gemini AI Service Error: " . $e->getMessage());
            }
        }

        // 3. Fallback: Both failed or no keys configured
        return $this->getFallbackResponse($messages);
    }

    /**
     * Call the Groq Chat Completion API
     */
    protected function callGroq(string $systemPrompt, array $messages): ?array
    {
        $formattedMessages = [];
        $formattedMessages[] = ['role' => 'system', 'content' => $systemPrompt];
        
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['content']
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->groqKey,
            'Content-Type' => 'application/json',
        ])->timeout(10)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile', // Groq's fast & smart model
            'messages' => $formattedMessages,
            'response_format' => ['type' => 'json_object'], // Enforce JSON
            'temperature' => 0.4,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? '';
            return json_decode($content, true);
        }

        Log::error("Groq API request failed: " . $response->body());
        return null;
    }

    /**
     * Call the Gemini API
     */
    protected function callGemini(string $systemPrompt, array $messages): ?array
    {
        // Construct the contents structure for Gemini
        $contents = [];
        
        // Gemini has its own way of handling system prompt or we can pass it as a user-like turn
        // For simplicity and compatibility, we combine system prompt into the first instruction or use systemInstruction field
        $geminiMessages = [];
        foreach ($messages as $msg) {
            $geminiMessages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['content']]]
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $this->geminiKey, [
            'contents' => $geminiMessages,
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json', // Enforce JSON
                'temperature' => 0.4,
            ]
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            return json_decode($content, true);
        }

        Log::error("Gemini API request failed: " . $response->body());
        return null;
    }

    /**
     * Local fallback response when AI is offline or keys are missing
     */
    protected function getFallbackResponse(array $messages): array
    {
        $lastUserMsg = '';
        foreach (array_reverse($messages) as $msg) {
            if ($msg['role'] === 'user') {
                $lastUserMsg = $msg['content'];
                break;
            }
        }

        // Standard support message
        $reply = "Désolé pour cette attente. Nos experts de l'atelier technique AutoImport Hub étudient actuellement votre demande. Veuillez saisir votre nom et numéro de téléphone afin qu'un conseiller vous rappelle directement pour planifier votre rendez-vous de révision.";
        
        // Try to extract basic words if possible (naive extraction for offline mode)
        $metadata = [
            'marque_vehicule' => null,
            'modele_vehicule' => null,
            'annee_vehicule' => null,
            'client_nom' => null,
            'client_telephone' => null,
            'client_email' => null,
            'probleme_description' => $lastUserMsg,
            'is_closed' => false,
            'summary' => "Demande de révision hors-ligne: " . $lastUserMsg
        ];

        // Simple offline flow check: if they gave something that looks like a phone number, we can close
        if (preg_match('/[0-9]{8,}/', $lastUserMsg)) {
            $metadata['is_closed'] = true;
            $metadata['client_telephone'] = $lastUserMsg;
            $reply = "Parfait, nous avons bien noté vos informations. Nos techniciens vous attendent à l'atelier AutoImport Hub. Nous vous recontacterons très rapidement pour confirmer l'heure exacte. Merci de votre confiance !";
        }

        return [
            'message' => $reply,
            'metadata' => $metadata
        ];
    }

    /**
     * Return the detailed system instruction prompt
     */
    protected function getSystemPrompt(): string
    {
        return <<<PROMPT
Vous êtes l'Assistant IA Expert de l'atelier de maintenance et révision automobile "AutoImport Hub".
Votre mission est de mener une discussion professionnelle avec le client pour diagnostiquer son problème de véhicule (voiture, moto, engin), collecter ses informations et finaliser son rendez-vous au garage (phase de Closing).

Vous devez obligatoirement répondre sous format JSON structuré avec exactement la structure suivante :
{
  "message": "Le message textuel en français chaleureux, précis et professionnel que le client lira sur le chat.",
  "metadata": {
    "marque_vehicule": "Marque détectée dans la discussion (ex: Lexus, Toyota, Peugeot) ou null",
    "modele_vehicule": "Modèle détecté dans la discussion (ex: RX350, Corolla, 308) ou null",
    "annee_vehicule": "Année du véhicule détectée ou null",
    "client_nom": "Nom et prénom du client s'il les a fournis ou null",
    "client_telephone": "Numéro de téléphone fourni par le client ou null",
    "client_email": "Email fourni par le client ou null",
    "probleme_description": "Description concise du problème mécanique ou technique détecté ou null",
    "is_closed": true (si vous avez collecté AU MOINS le modèle de voiture, la description du problème, le NOM et le TÉLÉPHONE du client, et que vous lui avez confirmé son accueil au garage) sinon false,
    "summary": "Un résumé technique complet et ultra-professionnel du problème du client destiné à l'administrateur du garage. Ce résumé doit inclure le diagnostic suspecté et les pièces ou interventions à prévoir (rempli uniquement quand is_closed est true, sinon null)"
  }
}

CONSIGNES DE CONVERSATION :
1. Accueil : Commencez par accueillir le client chaleureusement, présentez-vous comme l'expert IA d'AutoImport Hub, et demandez-lui quel est le problème avec son véhicule.
2. Écoute active et diagnostic : Posez des questions ciblées et professionnelles selon ce que décrit le client pour identifier la marque, le modèle et l'année. Donnez des conseils de pro (ex: "Si le voyant moteur est rouge, évitez de rouler", "Un sifflement au freinage indique souvent des plaquettes usées").
3. Closing (Clôture) : Lorsque vous avez le problème et le véhicule, demandez poliment ses coordonnées (Nom complet et Téléphone indispensable, Email optionnel) pour planifier l'accueil. Dites-lui clairement que l'équipe technique l'attend au garage.
4. Dès que vous avez le Nom et le Téléphone, mettez "is_closed" à true et générez le "summary" complet destiné au garage.
PROMPT;
    }
}
