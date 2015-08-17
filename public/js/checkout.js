var handler = null;
$(function () {

    handler = StripeCheckout.configure({
        key: 'pk_test_KioaeTnYy64CfKnL0a5kaoxB',
        image: './images/checkout-icon.gif',
        zipCode: true,
        address: true,
        token: function (token) {
            var csrf=$("[name='_token']").val();
            $.post( "subscribe", { token: token.id, _token: csrf} );
            window.location.href = '/applications';
        }
    });


    $('#checkout').on('click', function (e) {
        // Open Checkout with further options
        handler.open({
            name: 'XpressTek',
            description: 'SC Medicaid Converter 1 year subscription',
            amount: 9900
        });
        e.preventDefault();
    });

    // Close Checkout on page navigation
    $(window).on('popstate', function () {
        handler.close();
    });

});
