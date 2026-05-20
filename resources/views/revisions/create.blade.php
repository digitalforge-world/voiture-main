@php
    $hideFooter = true;
@endphp
@extends('layouts.app')

@section('title', 'Expert IA - Diagnostic & Révision')

@section('styles')
<style>
    /* Custom scrollbars for chat container */
    .chat-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .chat-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.15);
        border-radius: 9999px;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.3);
    }
    [x-cloak] {
        display: none !important;
    }
    
    /* Animation for smooth wave bar pulse */
    @keyframes wave-bounce {
        0%, 100% { transform: scaleY(0.4); }
        50% { transform: scaleY(1); }
    }
    .wave-bar {
        display: inline-block;
        width: 2px;
        height: 10px;
        background-color: currentColor;
        border-radius: 9999px;
        transform-origin: bottom;
    }
    .wave-bar-1 { animation: wave-bounce 1s ease-in-out infinite; }
    .wave-bar-2 { animation: wave-bounce 1s ease-in-out infinite 0.2s; }
    .wave-bar-3 { animation: wave-bounce 1s ease-in-out infinite 0.4s; }
</style>
@endsection

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="py-12 md:py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-500 min-h-[85vh] flex items-center justify-center relative">
    <div class="container mx-auto px-4 max-w-3xl" x-data="revisionChat()" x-init="initChat()" x-cloak>

        <!-- ================= 1. EMPTY STATE SCREEN (Mockup 1) ================= -->
        <div x-show="messages.length <= 1 && !isClosed" 
             class="w-full flex flex-col items-center justify-center text-center py-12 md:py-20 space-y-8 animate-in fade-in duration-500">
            
            <!-- Main Header Title -->
            <h2 class="text-2xl md:text-3xl font-medium text-slate-800 dark:text-slate-200 tracking-tight font-sans">
                Quel est le problème aujourd’hui ?
            </h2>

            <!-- Large Search Capsule Mockup -->
            <div class="w-full max-w-2xl">
                <!-- Image Preview Area -->
                <div x-show="selectedImage" class="flex items-center gap-2 mb-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-2xl w-fit animate-in slide-in-from-bottom-2 duration-200 shadow-sm mx-auto">
                    <img :src="imagePreviewUrl" class="w-8 h-8 rounded-lg object-cover border border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold max-w-[150px] truncate" x-text="selectedImageName"></span>
                    <button type="button" @click="clearSelectedImage()" class="text-slate-400 hover:text-red-500 p-0.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <form @submit.prevent="sendMessage()" class="w-full">
                    <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full py-2.5 pl-5 pr-3 shadow-sm focus-within:shadow-md focus-within:border-slate-350 dark:focus-within:border-slate-750 transition-all flex items-center gap-3">
                        
                        <!-- Left Plus Button (Manual Form trigger) -->
                        <button type="button" 
                                @click="showManualCloseForm = true" 
                                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all"
                                title="Formulaire Manuel">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </button>

                        <!-- Paperclip Upload Button -->
                        <button type="button" 
                                @click="$refs.imageInputStart.click()" 
                                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all"
                                title="Ajouter une image de la panne">
                            <i data-lucide="paperclip" class="w-4.5 h-4.5"></i>
                        </button>
                        <input type="file" 
                               x-ref="imageInputStart" 
                               accept="image/*" 
                               @change="handleImageUpload($event)" 
                               class="hidden">

                        <!-- Input textbox -->
                        <input type="text"
                               x-model="inputMessage"
                               placeholder="Décris le probleme de ton vehicule ou importer une image..."
                               class="flex-grow bg-transparent border-0 outline-none text-slate-800 dark:text-slate-200 placeholder-slate-400 font-semibold text-sm py-1.5">

                        <!-- Far Right Yellow Send Circular Button with soundwave wave-bar icons -->
                        <button type="submit" 
                                class="w-9 h-9 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center hover:bg-amber-600 active:scale-95 transition-all shadow-sm shrink-0 disabled:opacity-50"
                                :disabled="(!inputMessage.trim() && !selectedImage) || isLoading">
                            <div class="flex items-end gap-0.5 justify-center">
                                <span class="wave-bar wave-bar-1"></span>
                                <span class="wave-bar wave-bar-2"></span>
                                <span class="wave-bar wave-bar-3"></span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Below Capsule Pills (Pills 1, 2, 3 representing problem suggestions) -->
            <div class="flex flex-wrap items-center justify-center gap-2.5 max-w-xl">
                
                <button @click="sendSuggestion('Mon moteur claque au démarrage.')"
                        class="px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full text-[11px] font-semibold text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-850 transition-all flex items-center gap-1.5 shadow-sm hover:shadow">
                    <i data-lucide="wrench" class="w-3.5 h-3.5 text-slate-450"></i>
                    Bruit Moteur
                </button>

                <button @click="sendSuggestion('Ma voiture dégage de la fumée noire.')"
                        class="px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full text-[11px] font-semibold text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-850 transition-all flex items-center gap-1.5 shadow-sm hover:shadow">
                    <i data-lucide="wind" class="w-3.5 h-3.5 text-slate-450"></i>
                    Fumée Échappement
                </button>

                <button @click="sendSuggestion('Mes freins émettent des vibrations ou grincements.')"
                        class="px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full text-[11px] font-semibold text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-850 transition-all flex items-center gap-1.5 shadow-sm hover:shadow">
                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-slate-450"></i>
                    Problème de Freins
                </button>
                
            </div>
        </div>

        <!-- ================= 2. ACTIVE DISCUSSION SCREEN (Mockup 2) ================= -->
        <div x-show="messages.length > 1 || isClosed" 
             class="w-full flex flex-col justify-between min-h-[500px] pb-32 animate-in fade-in duration-300">
            
            <!-- Chat Header with Active Pulse and Reset option -->
            <div x-show="messages.length > 1 && !isClosed" class="w-full flex items-center justify-between border-b border-slate-200/50 dark:border-slate-800/50 pb-4 mb-6 animate-in slide-in-from-top-3 duration-300">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <h3 class="text-[9px] font-black uppercase tracking-widest text-slate-400">Diagnostic en cours...</h3>
                </div>
                <button @click="resetChat()" class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-rose-500 hover:text-white dark:bg-slate-900 dark:hover:bg-rose-950/40 text-slate-650 dark:text-slate-400 hover:dark:text-rose-400 border border-slate-200/30 dark:border-slate-800/30 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-300 active:scale-95 shadow-sm">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    Nouvelle Discussion
                </button>
            </div>

            <!-- Messages feed flow: flowing freely on the background canvas -->
            <div class="w-full space-y-8" id="chat-messages-container">
                
                <template x-for="(msg, index) in messages" :key="index">
                    <div class="w-full flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                        
                        <!-- User message: Rounded soft yellow capsule on the right with potential attached image -->
                        <template x-if="msg.role === 'user'">
                            <div class="flex flex-col items-end gap-2 max-w-[85%]">
                                <template x-if="msg.image">
                                    <div class="relative group rounded-3xl overflow-hidden shadow-md border border-amber-200 dark:border-amber-900/30">
                                        <img :src="msg.image" class="max-w-[280px] max-h-[200px] object-cover transition-transform group-hover:scale-105 duration-300">
                                    </div>
                                </template>
                                <div class="bg-amber-100/80 dark:bg-amber-500/10 text-slate-800 dark:text-amber-100 rounded-3xl rounded-tr-sm px-6 py-3 shadow-sm text-xs md:text-sm font-semibold select-text">
                                    <p class="whitespace-pre-wrap" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>

                        <!-- Assistant message: Plain text flowing on the left canvas without background bubble -->
                        <template x-if="msg.role !== 'user'">
                            <div class="w-full text-slate-800 dark:text-slate-200 text-xs md:text-sm leading-relaxed select-text py-2 max-w-[90%] font-medium">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-900 text-amber-500 flex items-center justify-center shadow-sm">
                                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-450">Expert Technique</span>
                                </div>
                                <div class="pl-8 whitespace-pre-wrap leading-relaxed markdown-style" x-text="msg.content"></div>
                            </div>
                        </template>

                    </div>
                </template>

                <!-- Typing indicator -->
                <div x-show="isLoading" class="w-full flex justify-start items-center gap-2 py-2">
                    <div class="w-6 h-6 rounded-full bg-slate-900 text-amber-500 flex items-center justify-center shadow-sm">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 animate-spin"></i>
                    </div>
                    <div class="flex items-center gap-1 pl-2">
                        <span class="w-1.5 h-1.5 bg-amber-500/40 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-1.5 h-1.5 bg-amber-500/60 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>

                <!-- Closing diagnostic receipt card -->
                <div x-show="isClosed" class="py-6 animate-in zoom-in duration-500 flex justify-center">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-xl max-w-sm w-full text-center space-y-5">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center mx-auto border border-emerald-500/25">
                            <i data-lucide="check-check" class="w-6 h-6"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter">Diagnostic Enregistré</h3>
                            <p class="text-[10px] text-slate-505 font-medium">Votre conseiller technique vous attend à l'atelier.</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-850 flex flex-col items-center">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Numéro Unique de Suivi</span>
                            <span class="text-lg font-black text-amber-500 tracking-widest font-mono select-all my-1 cursor-pointer" x-text="trackingNumber"></span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('tracking.index') }}" class="flex-1 py-3 bg-amber-500 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-slate-950 text-slate-950 font-black text-[9px] uppercase tracking-widest rounded-xl transition-all shadow-md italic">
                                Suivre ma Révision
                            </a>
                            <button @click="resetChat()" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-350 font-black text-[9px] uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-750 transition-all italic">
                                Recommencer
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Fixed Floating Input bar pinned at the bottom of the page -->
            <div class="fixed bottom-0 left-0 right-0 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-md pb-6 pt-4 z-40 border-t border-slate-200/40 dark:border-slate-800/40" x-show="!isClosed">
                <div class="container mx-auto px-4 max-w-2xl flex flex-col items-center">
                    
                    <!-- Detected Info Badges -->
                    <div class="flex flex-wrap gap-1.5 mb-2 w-full justify-start" x-show="metadata.marque_vehicule || metadata.client_nom">
                        <template x-if="metadata.marque_vehicule">
                            <span class="px-2 py-0.5 bg-amber-500/10 text-amber-500 text-[8px] font-black uppercase tracking-wider rounded border border-amber-500/20">
                                Voiture : <span x-text="metadata.marque_vehicule"></span>
                            </span>
                        </template>
                        <template x-if="metadata.client_nom">
                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-wider rounded border border-emerald-500/20">
                                Client : <span x-text="metadata.client_nom"></span>
                            </span>
                        </template>
                    </div>

                    <!-- Image Preview Area (Bottom Bar) -->
                    <div x-show="selectedImage" class="flex items-center gap-2 mb-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-2xl w-fit animate-in slide-in-from-bottom-2 duration-200 shadow-sm mr-auto">
                        <img :src="imagePreviewUrl" class="w-8 h-8 rounded-lg object-cover border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold max-w-[150px] truncate" x-text="selectedImageName"></span>
                        <button type="button" @click="clearSelectedImage()" class="text-slate-400 hover:text-red-500 p-0.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Input capsule matching start state exactly -->
                    <form @submit.prevent="sendMessage()" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full py-2 pl-4 pr-2.5 shadow-sm focus-within:shadow-md focus-within:border-slate-350 dark:focus-within:border-slate-750 transition-all flex items-center gap-2.5">
                        
                        <!-- Manual trigger button -->
                        <button type="button" 
                                @click="showManualCloseForm = true" 
                                class="w-7 h-7 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all"
                                title="Formulaire Manuel">
                            <i data-lucide="plus" class="w-4.5 h-4.5"></i>
                        </button>

                        <!-- Paperclip Upload Button -->
                        <button type="button" 
                                @click="$refs.imageInputBottom.click()" 
                                class="w-7 h-7 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all"
                                title="Ajouter une image de la panne">
                            <i data-lucide="paperclip" class="w-4 h-4"></i>
                        </button>
                        <input type="file" 
                               x-ref="imageInputBottom" 
                               accept="image/*" 
                               @change="handleImageUpload($event)" 
                               class="hidden">

                        <input type="text"
                               x-model="inputMessage"
                               placeholder="Poser une question..."
                               class="flex-grow bg-transparent border-0 outline-none text-slate-850 dark:text-slate-200 placeholder-slate-400 font-semibold text-xs py-1.5">

                        <!-- Send Button -->
                        <button type="submit" 
                                class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center hover:bg-amber-600 active:scale-95 transition-all shadow shrink-0 disabled:opacity-50"
                                :disabled="(!inputMessage.trim() && !selectedImage) || isLoading">
                            <i data-lucide="arrow-up" class="w-4 h-4 stroke-[3.5]"></i>
                        </button>
                    </form>

                    <!-- Disclaimer -->
                    <p class="text-[9px] text-slate-450 dark:text-slate-500 font-semibold tracking-wide mt-2 text-center">
                        L'IA d'AutoImport peut faire des erreurs. Veuillez toujours faire valider le diagnostic final par nos experts.
                    </p>
                </div>
            </div>

        </div>

    <!-- FLOATING MANUAL ENROLLMENT OVERLAY -->
    <div class="fixed inset-0 z-[100] bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" 
         x-show="showManualCloseForm" 
         x-transition
         x-cloak>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-md w-full p-8 shadow-2xl relative space-y-6 animate-in zoom-in duration-300">
            
            <button @click="showManualCloseForm = false" 
                    class="absolute top-6 right-6 text-slate-400 hover:text-slate-650 dark:hover:text-white p-1 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            
            <div class="space-y-1">
                <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Formulaire Manuel</h4>
                <p class="text-[9px] text-slate-450 dark:text-slate-500 font-black uppercase tracking-widest">Enregistrer votre rendez-vous sans passer par la discussion</p>
            </div>

            <form @submit.prevent="submitManualClose()" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Nom complet</label>
                    <input type="text" 
                           x-model="manualForm.client_nom" 
                           required 
                           placeholder="Ex: Moussa Traoré" 
                           class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-amber-500 rounded-2xl outline-none text-xs font-bold text-slate-800 dark:text-white transition-colors">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Téléphone de contact</label>
                    <input type="tel" 
                           x-model="manualForm.client_telephone" 
                           required 
                           placeholder="Ex: +223 70 00 00 00" 
                           class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-amber-500 rounded-2xl outline-none text-xs font-bold text-slate-800 dark:text-white transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Adresse E-mail (Optionnelle)</label>
                    <input type="email" 
                           x-model="manualForm.client_email" 
                           placeholder="Ex: client@gmail.com" 
                           class="w-full p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-amber-500 rounded-2xl outline-none text-xs font-bold text-slate-800 dark:text-white transition-colors">
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-amber-500 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-slate-950 text-slate-950 font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg hover:shadow-xl transition-all italic mt-2">
                    Enregistrer ma Demande
                </button>
            </form>
        </div>
    </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function revisionChat() {
    return {
        conversationId: null,
        messages: [],
        inputMessage: '',
        isLoading: false,
        isClosed: false,
        trackingNumber: null,
        metadata: {
            client_nom: '',
            client_telephone: '',
            marque_vehicule: '',
            modele_vehicule: ''
        },
        showManualCloseForm: false,
        manualForm: {
            client_nom: '',
            client_telephone: '',
            client_email: ''
        },
        
        // Image attachment states
        selectedImage: null,
        imagePreviewUrl: '',
        selectedImageName: '',

        async initChat() {
            try {
                const cachedId = localStorage.getItem('active_revision_chat_id');
                const response = await fetch("{{ route('revisions.chat.start') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: cachedId
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.conversationId = data.conversation_id;
                    this.messages = data.messages;
                    localStorage.setItem('active_revision_chat_id', data.conversation_id);
                    
                    if (data.is_closed) {
                        this.isClosed = true;
                        localStorage.removeItem('active_revision_chat_id');
                    }
                    
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                    this.scrollToBottom();
                } else {
                    if (cachedId) {
                        localStorage.removeItem('active_revision_chat_id');
                        await this.initChat();
                    }
                }
            } catch (err) {
                console.error("Failed to start chat session", err);
            }
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedImage = file;
                this.selectedImageName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviewUrl = e.target.result;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                };
                reader.readAsDataURL(file);
            }
        },

        clearSelectedImage() {
            this.selectedImage = null;
            this.imagePreviewUrl = '';
            this.selectedImageName = '';
            if (this.$refs.imageInputStart) this.$refs.imageInputStart.value = '';
            if (this.$refs.imageInputBottom) this.$refs.imageInputBottom.value = '';
        },

        async sendSuggestion(suggestionText) {
            this.inputMessage = suggestionText;
            await this.sendMessage();
        },

        async sendMessage() {
            if ((!this.inputMessage.trim() && !this.selectedImage) || this.isLoading || this.isClosed) return;

            const userText = this.inputMessage || "Image de la panne mécanique";
            const userImg = this.imagePreviewUrl;
            this.inputMessage = '';
            this.clearSelectedImage();
            
            // Add user message locally with image support
            this.messages.push({
                role: 'user',
                content: userText,
                image: userImg,
                timestamp: new Date().toISOString()
            });
            
            this.isLoading = true;
            this.scrollToBottom();
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            try {
                const response = await fetch("{{ route('revisions.chat.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: this.conversationId,
                        message: userText,
                        image: userImg
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    this.messages.push({
                        role: 'assistant',
                        content: data.message,
                        timestamp: new Date().toISOString()
                    });

                    this.isClosed = data.is_closed;
                    if (data.is_closed) {
                        localStorage.removeItem('active_revision_chat_id');
                    }
                    if (data.tracking_number) {
                        this.trackingNumber = data.tracking_number;
                    }

                    if (data.metadata) {
                        this.metadata = { ...this.metadata, ...data.metadata };
                    }
                } else {
                    this.messages.push({
                        role: 'assistant',
                        content: "Désolé, j'ai rencontré un problème pour traiter votre message. Veuillez réessayer ou utiliser le formulaire manuel.",
                        timestamp: new Date().toISOString()
                    });
                }
            } catch (err) {
                console.error("Chat message error", err);
                this.messages.push({
                    role: 'assistant',
                    content: "Nos serveurs sont actuellement surchargés. Veuillez patienter ou utiliser le bouton 'Remplir Manuellement' pour finaliser directement.",
                    timestamp: new Date().toISOString()
                });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            }
        },

        async submitManualClose() {
            if (!this.manualForm.client_nom || !this.manualForm.client_telephone) return;

            try {
                const response = await fetch("{{ route('revisions.chat.close') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: this.conversationId,
                        ...this.manualForm
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.trackingNumber = data.tracking_number;
                    this.isClosed = true;
                    this.showManualCloseForm = false;
                    localStorage.removeItem('active_revision_chat_id');
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                }
            } catch (err) {
                console.error("Manual close error", err);
            }
        },

         resetChat() {
             localStorage.removeItem('active_revision_chat_id');
             this.conversationId = null;
             this.messages = [];
             this.inputMessage = '';
             this.isLoading = false;
             this.isClosed = false;
             this.trackingNumber = null;
             this.metadata = {
                 client_nom: '',
                 client_telephone: '',
                 marque_vehicule: '',
                 modele_vehicule: ''
             };
             this.showManualCloseForm = false;
             this.manualForm = {
                 client_nom: '',
                 client_telephone: '',
                 client_email: ''
             };
             this.clearSelectedImage();
             this.initChat();
         },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = document.getElementById('chat-messages-container');
                if (el) {
                    el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
                }
            });
        }
    }
}
</script>
@endsection
