$(document).ready(function() {
    $('#myForm').on('submit', function(e) {
        e.preventDefault(); // Остановка отправки формы

        $(".h6-update").addClass("d-none");

        let savingsBlock = $('[name="savings_type"]');
        let savings = savingsBlock.val();
        savingsBlock.removeClass('bg-danger');

        let valueBlock = $('[name="value"]');
        let value = valueBlock.val();
        valueBlock.removeClass('bg-danger');

        let id = new URLSearchParams(window.location.search).get("id");

        $.ajax({
            url: "/backend/script/savings/update_savings.php",
            method: 'POST',
            dataType: 'html',
            data: {
                id: id,
                savings: savings,
                value: value
            },
            success: function (data) {
                if(data === "savings"){
                    savingsBlock.focus().addClass("bg-danger")
                }
                if(data === "value"){
                    valueBlock.focus().addClass("bg-danger")
                }
                if(data === "error"){
                    alert("Попробуйте еще раз");
                }
                if(data === "update"){
                    $(".h6-update").removeClass("d-none");
                }
            }
        });
    });
});