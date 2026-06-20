@extends('layouts.admin')

@section('title', 'Transport avec Chauffeur - Admin')

@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
        🚗 Transport avec Chauffeur
        @if($stats['en_attente'] > 0)
        <span class="inline-flex items-center justify-center w-7 h-7 bg-red-500 text-white text-xs font-bold rounded-full animate-bounce">
          {{ $stats['en_attente'] }}
        </span>
        @endif
      </h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestion des réservations de transport et chauffeurs</p>
    </div>
    <a href="{{ route('transport.index') }}" target="_blank"
      class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 rounded-lg shadow-sm transition">
      <i data-lucide="external-link" class="w-4 h-4"></i> Page publique
    </a>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
    @foreach([
      ['label' => 'Total', 'value' => $stats['total'], 'color' => 'slate', 'icon' => 'map'],
      ['label' => 'En attente', 'value' => $stats['en_attente'], 'color' => 'red', 'icon' => 'clock', 'alert' => true],
      ['label' => 'En cours', 'value' => $stats['en_cours'], 'color' => 'amber', 'icon' => 'navigation'],
      ['label' => 'Terminé', 'value' => $stats['termine'], 'color' => 'emerald', 'icon' => 'check-circle'],
      ['label' => 'Revenus', 'value' => number_format($stats['revenus'], 0, ',', ' ') . ' FCFA', 'color' => 'blue', 'icon' => 'banknote'],
    ] as $s)
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm
      {{ ($s['alert'] ?? false) && $stats['en_attente'] > 0 ? 'border-l-4 border-l-red-500' : '' }}">
      <div class="flex items-center gap-3">
        <div class="p-2.5 bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 rounded-lg">
          <i data-lucide="{{ $s['icon'] }}" class="w-5 h-5 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
        </div>
        <div>
          <div class="text-xs text-slate-500">{{ $s['label'] }}</div>
          <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $s['value'] }}</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Filtres --}}
  <div class="flex gap-2 flex-wrap">
    <a href="{{ route('admin.transport.index') }}"
      class="px-3 py-1.5 text-xs font-semibold rounded-lg transition {{ !request('statut') ? 'bg-amber-500 text-slate-950' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
      Tous
    </a>
    @foreach(['en_attente' => '⏳ En attente', 'accepte' => '✅ Accepté', 'chauffeur_en_route' => '🚗 En route', 'chauffeur_arrive' => '📍 Arrivé', 'en_cours' => '🚀 En cours', 'termine' => '🏁 Terminé', 'annule' => '❌ Annulé'] as $val => $label)
    <a href="{{ route('admin.transport.index', ['statut' => $val]) }}"
      class="px-3 py-1.5 text-xs font-semibold rounded-lg transition {{ request('statut') === $val ? 'bg-amber-500 text-slate-950' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
      {{ $label }}
    </a>
    @endforeach
  </div>

  {{-- Table --}}
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
          <tr>
            <th class="px-5 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Client / Réf</th>
            <th class="px-5 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Trajet</th>
            <th class="px-5 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Date & Type</th>
            <th class="px-5 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut & Prix</th>
            <th class="px-5 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @forelse($reservations as $r)
          @php
            $sc = match($r->statut) {
              'en_attente'          => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
              'accepte'             => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
              'chauffeur_en_route'  => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
              'chauffeur_arrive'    => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
              'en_cours'            => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
              'termine'             => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
              'annule'              => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
              default               => 'bg-slate-100 text-slate-500',
            };
          @endphp
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
            <td class="px-5 py-4">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500 font-bold text-sm flex-shrink-0">
                  {{ strtoupper(substr($r->client_nom, 0, 1)) }}
                </div>
                <div>
                  <div class="font-semibold text-sm text-slate-900 dark:text-white">{{ $r->client_nom }}</div>
                  <div class="text-xs text-amber-500 font-mono">{{ $r->tracking_number }}</div>
                  <div class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                    <span>📞</span> {{ $r->client_telephone }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-5 py-4">
              <div class="space-y-1 max-w-xs">
                <div class="text-xs text-slate-900 dark:text-white flex items-start gap-1">
                  <span class="text-emerald-500 flex-shrink-0">📍</span>
                  <span class="line-clamp-1">{{ Str::limit($r->lieu_depart, 40) }}</span>
                </div>
                <div class="text-xs text-slate-500 flex items-start gap-1">
                  <span class="text-rose-500 flex-shrink-0">🏁</span>
                  <span class="line-clamp-1">{{ Str::limit($r->lieu_arrivee, 40) }}</span>
                </div>
              </div>
            </td>
            <td class="px-5 py-4">
              <div class="text-xs text-slate-900 dark:text-white font-medium">{{ $r->date_prise_en_charge->format('d/m/Y') }}</div>
              <div class="text-xs text-slate-500">{{ $r->date_prise_en_charge->format('H:i') }}</div>
              <div class="text-xs text-slate-400 mt-1">{{ \App\Models\ReservationTransport::typeServiceLabels()[$r->type_service] }}</div>
            </td>
            <td class="px-5 py-4">
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">
                {{ $r->statut_label }}
              </span>
              @if($r->prix_propose)
              <div class="mt-1.5 text-xs">
                <span class="text-slate-500">Prix: </span>
                <span class="font-bold text-slate-900 dark:text-white">{{ number_format($r->prix_propose, 0, ',', ' ') }} FCFA</span>
                @if($r->prix_accepte)
                <span class="ml-1 text-emerald-500">✅</span>
                @else
                <span class="ml-1 text-amber-500">⏳</span>
                @endif
              </div>
              @else
              <div class="mt-1 text-xs text-slate-500 italic">Prix à négocier</div>
              @endif
            </td>
            <td class="px-5 py-4 text-right">
              <a href="{{ route('admin.transport.show', $r->id) }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-500/10 hover:bg-amber-500 text-amber-600 dark:text-amber-400 hover:text-slate-950 border border-amber-500/20 rounded-lg transition">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Gérer
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-5 py-12 text-center text-slate-500 text-sm">
              <div class="text-3xl mb-2">🚗</div>
              Aucune réservation de transport pour le moment.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($reservations->hasPages())
    <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800">
      {{ $reservations->links() }}
    </div>
    @endif
  </div>

</div>
@endsection
