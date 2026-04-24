@extends('layouts.app')

@section('title', 'Pagamento - Crofline')

@section('content')
<style>
    .pay-wrapper {
        max-width: 1320px;
        margin: 34px auto 70px;
        padding: 0 20px;
        color: var(--crofline-texto);
    }

    .pay-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(300px, .9fr);
        gap: 28px;
        align-items: start;
    }

    .pay-card, .pay-summary {
        border-radius: 24px;
        padding: 24px;
        background:
            radial-gradient(circle at top right, rgba(76, 29, 149, 0.16), transparent 24%),
            radial-gradient(circle at bottom left, rgba(109, 40, 217, 0.18), transparent 28%),
            linear-gradient(135deg, rgba(22, 6, 43, 0.96), rgba(42, 11, 79, 0.96));
        border: 1px solid rgba(255,255,255,0.07);
        box-shadow: 0 18px 40px rgba(0,0,0,.32);
    }

    .pay-title {
        margin: 0 0 18px;
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }

    .pay-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .pay-method-btn {
        border: 1px solid rgba(255,255,255,.08);
        background: rgba(255,255,255,.04);
        color: #fff;
        border-radius: 18px;
        padding: 16px;
        cursor: pointer;
        font-weight: 700;
        transition: .2s;
    }

    .pay-method-btn.active {
        background: #751597;
        border-color: transparent;
    }

    .pay-section {
        display: none;
        margin-top: 16px;
    }

    .pay-section.active {
        display: block;
    }

    .pay-input,
    .pay-select {
        width: 100%;
        border-radius: 14px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.12);
        color: #fff;
        padding: 12px 14px;
        margin-bottom: 14px;
    }

    .pay-select option {
        color: #111;
    }

    .pay-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .pay-btn {
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 14px 18px;
        background: #751597;
        color: #fff;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        transition: .2s;
    }

    .pay-btn:hover {
        filter: brightness(1.06);
        transform: translateY(-1px);
    }

    .pay-summary-line,
    .pay-summary-total {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
    }

    .pay-summary-total {
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid rgba(255,255,255,.08);
        font-weight: 700;
    }

    .pay-items {
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pay-item {
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,.06);
        font-size: .9rem;
    }

    .pay-item:last-child {
        border-bottom: none;
    }

    .pay-card-preview {
        height: 160px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.03));
        border: 1px solid rgba(255,255,255,.08);
        margin-top: 14px;
    }

    @media (max-width: 980px) {
        .pay-grid {
            grid-template-columns: 1fr;
        }

        .pay-methods {
            grid-template-columns: 1fr;
        }

        .pay-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $total = $checkout['total'] ?? 0;
    $produtos = $checkout['produtos'] ?? [];
@endphp

<div class="pay-wrapper">
    <div class="pay-grid">
        <div class="pay-card">
            <h2 class="pay-title">Escolha a forma de pagamento</h2>

            <div class="pay-methods">
                <button type="button" class="pay-method-btn active" data-method="pix">Pix</button>
                <button type="button" class="pay-method-btn" data-method="credito">Crédito</button>
                <button type="button" class="pay-method-btn" data-method="debito">Débito</button>
            </div>

            <div class="pay-section active" data-section="pix">
                <form method="POST" action="{{ route('pagamento.pix') }}">
                    @csrf
                    <input type="text" class="pay-input" name="payer_name" placeholder="Nome do titular" value="{{ $userName }}" required>
                    <input type="email" class="pay-input" name="payer_email" placeholder="E-mail" value="{{ $userEmail }}" required>

                    <div class="pay-grid-2">
                        <select class="pay-select" name="doc_type">
                            <option value="CPF">CPF</option>
                            <option value="CNPJ">CNPJ</option>
                        </select>
                        <input type="text" class="pay-input" name="doc_number" placeholder="Documento do titular">
                    </div>

                    <button type="submit" class="pay-btn">Gerar Pix</button>
                </form>
            </div>

            <div class="pay-section" data-section="credito">
                <form id="form-cartao-credito" method="POST" action="{{ route('pagamento.cartao') }}">
                    @csrf
                    <input type="hidden" name="payment_type_choice" value="credito">
                    <input type="hidden" name="token" id="mp-token-credito">
                    <input type="hidden" name="payment_method_id" id="mp-payment-method-id-credito">
                    <input type="hidden" name="issuer_id" id="mp-issuer-id-credito">
                    <input type="hidden" name="installments" id="mp-installments-credito" value="1">
                    <input type="hidden" name="identification_type" id="mp-identification-type-credito">
                    <input type="hidden" name="identification_number" id="mp-identification-number-credito">

                    <input type="email" class="pay-input" id="form-card-email-credito" name="payer_email" value="{{ $userEmail }}" placeholder="Seu e-mail" required>
                    <input type="text" class="pay-input" id="form-card-holder-credito" placeholder="Nome do titular" required>

                    <div class="pay-grid-2">
                        <input type="text" class="pay-input" id="form-card-number-credito" placeholder="0000 0000 0000 0000" required>
                        <input type="text" class="pay-input" id="form-card-expiration-credito" placeholder="MM/AA" required>
                    </div>

                    <div class="pay-grid-2">
                        <input type="text" class="pay-input" id="form-card-cvv-credito" placeholder="CVV" required>
                        <select class="pay-select" id="form-card-installments-credito"></select>
                    </div>

                    <div class="pay-grid-2">
                        <select class="pay-select" id="form-doc-type-credito"></select>
                        <input type="text" class="pay-input" id="form-doc-number-credito" placeholder="Documento do titular" required>
                    </div>

                    <select class="pay-select" id="form-card-issuer-credito" style="display:none;"></select>

                    <div class="pay-card-preview"></div>

                    <button type="submit" class="pay-btn">Pagar com crédito</button>
                </form>
            </div>

            <div class="pay-section" data-section="debito">
                <form id="form-cartao-debito" method="POST" action="{{ route('pagamento.cartao') }}">
                    @csrf
                    <input type="hidden" name="payment_type_choice" value="debito">
                    <input type="hidden" name="token" id="mp-token-debito">
                    <input type="hidden" name="payment_method_id" id="mp-payment-method-id-debito">
                    <input type="hidden" name="issuer_id" id="mp-issuer-id-debito">
                    <input type="hidden" name="installments" id="mp-installments-debito" value="1">
                    <input type="hidden" name="identification_type" id="mp-identification-type-debito">
                    <input type="hidden" name="identification_number" id="mp-identification-number-debito">

                    <input type="email" class="pay-input" id="form-card-email-debito" name="payer_email" value="{{ $userEmail }}" placeholder="Seu e-mail" required>
                    <input type="text" class="pay-input" id="form-card-holder-debito" placeholder="Nome do titular" required>

                    <div class="pay-grid-2">
                        <input type="text" class="pay-input" id="form-card-number-debito" placeholder="0000 0000 0000 0000" required>
                        <input type="text" class="pay-input" id="form-card-expiration-debito" placeholder="MM/AA" required>
                    </div>

                    <div class="pay-grid-2">
                        <input type="text" class="pay-input" id="form-card-cvv-debito" placeholder="CVV" required>
                        <select class="pay-select" id="form-card-installments-debito">
                            <option value="1">1x</option>
                        </select>
                    </div>

                    <div class="pay-grid-2">
                        <select class="pay-select" id="form-doc-type-debito"></select>
                        <input type="text" class="pay-input" id="form-doc-number-debito" placeholder="Documento do titular" required>
                    </div>

                    <select class="pay-select" id="form-card-issuer-debito" style="display:none;"></select>

                    <div class="pay-card-preview"></div>

                    <button type="submit" class="pay-btn">Pagar com débito</button>
                </form>
            </div>
        </div>

        <div class="pay-summary">
            <h3 class="pay-title">Resumo da compra</h3>

            <div class="pay-summary-line">
                <span>Produtos</span>
                <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
            </div>

            <div class="pay-summary-line">
                <span>Frete</span>
                <span>Grátis</span>
            </div>

            <div class="pay-summary-total">
                <span>Você pagará</span>
                <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
            </div>

            <div class="pay-items">
                @foreach($produtos as $produto)
                    <div class="pay-item">
                        <strong>#{{ $produto['id_produto'] }}</strong><br>
                        Cor: {{ $produto['cor'] }} | Tamanho: {{ $produto['tamanho'] }} | Qtd: {{ $produto['quantidade'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('[data-method]');
    const sections = document.querySelectorAll('[data-section]');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.getAttribute('data-method');

            buttons.forEach(b => b.classList.remove('active'));
            sections.forEach(s => s.classList.remove('active'));

            this.classList.add('active');
            document.querySelector('[data-section="' + target + '"]').classList.add('active');
        });
    });

    const publicKey = "{{ env('MERCADOPAGO_PUBLIC_KEY') }}";
    if (!publicKey) return;

    const mp = new MercadoPago(publicKey, { locale: 'pt-BR' });
    const amount = "{{ number_format($total, 2, '.', '') }}";

    function initCardForm(prefix, typeChoice) {
        const formId = `form-cartao-${prefix}`;

        if (!document.getElementById(formId)) return;

        const cardForm = mp.cardForm({
            amount: amount,
            iframe: true,
            form: {
                id: formId,
                cardNumber: {
                    id: `form-card-number-${prefix}`,
                    placeholder: "0000 0000 0000 0000"
                },
                expirationDate: {
                    id: `form-card-expiration-${prefix}`,
                    placeholder: "MM/AA"
                },
                securityCode: {
                    id: `form-card-cvv-${prefix}`,
                    placeholder: "CVV"
                },
                cardholderName: {
                    id: `form-card-holder-${prefix}`,
                    placeholder: "Nome do titular"
                },
                issuer: {
                    id: `form-card-issuer-${prefix}`
                },
                installments: {
                    id: `form-card-installments-${prefix}`
                },
                identificationType: {
                    id: `form-doc-type-${prefix}`
                },
                identificationNumber: {
                    id: `form-doc-number-${prefix}`,
                    placeholder: "Documento"
                },
                cardholderEmail: {
                    id: `form-card-email-${prefix}`,
                    placeholder: "email@exemplo.com"
                }
            },
            callbacks: {
                onFormMounted: error => {
                    if (error) console.warn(error);
                },
                onSubmit: event => {
                    event.preventDefault();

                    const data = cardForm.getCardFormData();

                    document.getElementById(`mp-token-${prefix}`).value = data.token || '';
                    document.getElementById(`mp-payment-method-id-${prefix}`).value = data.paymentMethodId || '';
                    document.getElementById(`mp-issuer-id-${prefix}`).value = data.issuerId || '';
                    document.getElementById(`mp-installments-${prefix}`).value = data.installments || 1;
                    document.getElementById(`mp-identification-type-${prefix}`).value = data.identificationType || '';
                    document.getElementById(`mp-identification-number-${prefix}`).value = data.identificationNumber || '';

                    document.getElementById(formId).submit();
                }
            }
        });
    }

    initCardForm('credito', 'credito');
    initCardForm('debito', 'debito');
});
</script>
@endsection