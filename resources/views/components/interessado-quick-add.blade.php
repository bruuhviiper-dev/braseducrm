@props([
    'target',              // name do <select> de interessados que recebe a nova opção
    'label' => 'Novo interessado',
])
{{-- Cadastro rápido de interessado/lead (padrão EDUQ): "+" abre modal, salva via AJAX e injeta a opção sem sair da tela --}}
<div x-data="interessadoQuickAdd('{{ $target }}', '{{ route('crm.interessados.quick') }}', '{{ csrf_token() }}')" class="inline-block">
    <button type="button" @click="abrir()" class="text-xs text-cyan-600 hover:underline" title="Cadastrar interessado sem sair da tela">
        <i class="fa-solid fa-plus mr-0.5"></i>{{ $label }}
    </button>

    <div x-show="aberto" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="aberto = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Cadastro rápido de interessado</h3>
            <p class="text-xs text-gray-400 mb-4">Cria o lead na hora e já o seleciona no campo — depois você completa a ficha em Interessados.</p>
            <div class="space-y-3">
                <input type="text" x-model="form.nome" placeholder="Nome *" class="w-full border rounded-lg px-3 py-2 text-sm" @keydown.enter.prevent="salvar()">
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" x-model="form.cpf" placeholder="CPF" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <input type="text" x-model="form.celular" placeholder="Celular" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <input type="email" x-model="form.email" placeholder="E-mail" class="w-full border rounded-lg px-3 py-2 text-sm">
                <p x-show="erro" x-text="erro" class="text-xs text-red-500"></p>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="aberto = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="button" @click="salvar()" :disabled="salvando" class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg text-sm font-semibold disabled:opacity-50">
                        <span x-show="!salvando">Salvar</span><span x-show="salvando">Salvando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
function interessadoQuickAdd(targetName, url, token) {
    return {
        aberto: false, salvando: false, erro: '',
        form: { nome: '', cpf: '', celular: '', email: '' },
        abrir() { this.erro = ''; this.form = { nome: '', cpf: '', celular: '', email: '' }; this.aberto = true; },
        async salvar() {
            if (!this.form.nome.trim()) { this.erro = 'Informe o nome.'; return; }
            this.salvando = true; this.erro = '';
            try {
                const r = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    body: JSON.stringify(this.form),
                });
                if (!r.ok) { const e = await r.json().catch(() => ({})); this.erro = e.message || 'Não foi possível cadastrar.'; this.salvando = false; return; }
                const data = await r.json();
                document.querySelectorAll('select[name="' + targetName + '"], select[name="' + targetName + '[]"]').forEach(sel => {
                    const opt = new Option(data.label, data.id, true, true);
                    sel.add(opt);
                    sel.dispatchEvent(new Event('change', { bubbles: true }));
                    sel.dispatchEvent(new Event('input', { bubbles: true }));
                });
                this.aberto = false;
            } catch (e) { this.erro = 'Falha de conexão.'; }
            this.salvando = false;
        },
    };
}
</script>
@endPushOnce
