<style>
    .billing-toggle {
        display: inline-flex;
        align-items: center;
        padding: 4px;
        border-radius: 50px;
        background: #eef2f6;
        border: 1px solid #dde4ec;
        gap: 4px;
    }

    .billing-toggle__btn {
        appearance: none;
        border: 0;
        background: transparent;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 600;
        color: #6b7a8a;
        cursor: pointer;
        transition: color .15s ease, background .15s ease, box-shadow .15s ease;
    }

    .billing-toggle__btn:hover {
        color: #0053a0;
    }

    .billing-toggle__btn.active {
        background: #0053a0;
        color: #fff;
        box-shadow: 0 2px 6px rgba(0, 83, 160, 0.35);
    }

    .billing-toggle__save {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 50px;
        background: #ff6b1a;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        vertical-align: middle;
    }

    .billing-toggle__btn.active .billing-toggle__save {
        background: #fff;
        color: #ff6b1a;
    }
</style>

<div class="billing-toggle" id="billing-toggle" role="group" aria-label="Billing period">
    <button type="button" class="billing-toggle__btn active" data-billing="monthly">Monthly</button>
    <button type="button" class="billing-toggle__btn" data-billing="yearly">Yearly <span class="billing-toggle__save">Save 10%</span></button>
</div>
