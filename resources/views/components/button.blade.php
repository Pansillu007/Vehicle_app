<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none']) }}>
    {{ $slot }}
</button>