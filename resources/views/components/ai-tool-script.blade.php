{{-- Shared Alpine helper for CV-upload based AI tools --}}
<script>
    function aiToolBase(endpoint) {
        return {
            file: null,
            loading: false,
            error: null,
            result: null,
            endpoint,
            // Override in child component to append extra form fields.
            extraFields(form) {},
            handleFile(event) {
                const selected = event.target.files[0];
                this.error = null;
                this.result = null;
                if (!selected) return;
                if (selected.type !== 'application/pdf' && !selected.name.toLowerCase().endsWith('.pdf')) {
                    this.error = 'CV harus berupa file PDF.';
                    event.target.value = '';
                    return;
                }
                if (selected.size > 5 * 1024 * 1024) {
                    this.error = 'Ukuran file CV maksimal 5MB.';
                    event.target.value = '';
                    return;
                }
                this.file = selected;
            },
            async submit() {
                if (this.loading || !this.file) return;
                this.loading = true;
                this.error = null;
                this.result = null;

                const form = new FormData();
                form.append('pdf_file', this.file);
                this.extraFields(form);

                try {
                    const response = await fetch(this.endpoint, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: form,
                    });
                    const data = await response.json();
                    if (!response.ok) {
                        const validation = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                        throw new Error(data.message || validation || 'Pemrosesan gagal.');
                    }
                    this.result = data.result;
                    this.$nextTick(() => document.querySelector('[x-show="result"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
                } catch (err) {
                    this.error = err.message || 'Pemrosesan gagal. Coba lagi sebentar.';
                } finally {
                    this.loading = false;
                }
            },
            copyText(text, event) {
                if (!text) return;
                navigator.clipboard?.writeText(text);
                const el = event?.target;
                if (el) {
                    const original = el.textContent;
                    el.textContent = 'Tersalin!';
                    setTimeout(() => { el.textContent = original; }, 1500);
                }
            },
        };
    }
</script>
