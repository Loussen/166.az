<section class="page-footer">
    <div class="container page-footer__container" x-subscribe-area>
        <div class="page-footer__description">
            <h5>{{ __('message.Subscribe_to_newsletters') }}</h5>
            <p>{{ __('message.Subscribe_content') }}.</p>
        </div>
        <form action="{{ route( 'subscribe' ) }}" class="page-footer__form" method="post" x-edit-form x-target="afterSubscribe">
            <input name="email" type="text" placeholder="{{ __('message.Placeholder_email') }}…">
            <input type="submit" value="{{ __('message.Subscribe') }}">
        </form>
    </div>
</section>

<script>
    function afterSubscribe()
    {
        $( '[x-target="afterSubscribe"]' ).fadeOut( function()
        {
            $( this ).remove();
        } );

        $( '[x-subscribe-area]' ).append( '<span>{{ __('message.Thank_you_for_subscribing') }}</span>' );
    }
</script>

