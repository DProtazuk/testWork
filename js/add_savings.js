$(document).ready(function() {
    $('#myForm').on('submit', function(e) {
        e.preventDefault(); // Остановка отправки формы

        let savingsBlock = $('[name="savings_type"]');
        let savings = savingsBlock.val();
        savingsBlock.removeClass('bg-danger');

        let currencyBlock = $('[name="currency"]');
        let currency = currencyBlock.val();
        currencyBlock.removeClass('bg-danger');

        let valueBlock = $('[name="value"]');
        let value = valueBlock.val();
        valueBlock.removeClass('bg-danger');

        $.ajax({
            url: "/backend/script/savings/add_savings.php",
            method: 'POST',
            dataType: 'html',
            data: {
                savings: savings,
                currency: currency,
                value: value
            },
            success: function (data) {

                console.log(data);
                if(data === "savings"){
                    savingsBlock.focus().addClass("bg-danger")
                }
                if(data === "currency"){
                    currencyBlock.focus().addClass("bg-danger")
                }
                if(data === "value"){
                    valueBlock.focus().addClass("bg-danger")
                }
                if(data === "error"){
                    alert("Попробуйте еще раз");
                }
                if(data === "save"){
                    window.location.href = "/page/pillow/pillow.php";
                }
            }
        });
    });
});

