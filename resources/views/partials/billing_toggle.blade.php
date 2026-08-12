{{-- Markup only; the .billing-toggle styles are pushed to the head via @push('styles') in each package view. --}}
<div class="billing-toggle" id="billing-toggle" role="group" aria-label="Billing period">
    <button type="button" class="billing-toggle__btn active" data-billing="monthly">Monthly</button>
    <button type="button" class="billing-toggle__btn" data-billing="yearly">Yearly <span class="billing-toggle__save">Save 10%</span></button>
</div>
