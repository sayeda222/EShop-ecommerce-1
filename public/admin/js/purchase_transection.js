let initial = 0;
function addrow() {
    let str = "";
    initial++;
    str +=
        '<tr id="div_' +
        Number(initial) +
        '">' +
        "<td>" +
        Number(initial) +
        "</td>" +
        "<td>" +
        '<select class="js-data-example-ajax form-control " onchange="productwiseprice(this.value,Number(initial))" id="product-' +
        Number(initial) +
        '">' +
        "</select>" +
        "</td>" +
        "<td>" +
        '<input type="text" size="2" onkeyup="productQty(this.value,Number(initial))" id="qty-' +
        Number(initial) +
        '"  class="form-control input-sm" name="quantity[]"/>' +
        "</td>" +
        "<td>" +
        '<input type="number" size="2" id="price-' +
        Number(initial) +
        '" class="form-control input-sm" name="price[]" />' +
        "</td>" +
        "<td>" +
        '<input type="number" size="2" id="discount-'+Number(initial)+'" onchange="discount(this.value,Number(initial))" class="form-control input-sm" name="discount[]" />' +
        "</td>" +
        "<td>" +
        '<input type="number" readonly id="net-' +
        Number(initial) +
        '" size="2" class="form-control input-sm netAmounts" id="net-'+Number(initial)+'" name="net[]"  />' +
        '<input type="hidden" readonly id="hiddennet-'+Number(initial)+'" + Number(initial) + " size="2" class="form-control input-sm" name="hiddennet[]"  />' +
        "</td>" +
        "<td>" +
        '<button id="add_row" onclick="addrow()" class="btn btn-success pull-left btn-sm"> ' +
        "+" +
        "</button>" +
        '<button id="delete_row" onclick="remove_row(' +
        Number(initial) +
        ')" class="pull-right btn btn-danger btn-sm"> ' +
        "-" +
        "</button>" +
        "</td>" +
        "</tr>";

    $("#dynamicRow").append(str);
    remote_select("js-data-example-ajax", Number(initial));
}



$(document).ready(function () {
    $("#formId").on("submit", function (event) {
        event.preventDefault();
        let request = $("#formId").serializeArray();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "save-purshase-transection",
            method: "POST",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {},
            success: function (response) {
                Swal.fire({
                    html: "I will close in <b></b> milliseconds.",
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        timerInterval = setInterval(() => {
                            const content = Swal.getContent();
                            if (content) {
                                const b = content.querySelector("b");
                                if (b) {
                                    b.textContent = Swal.getTimerLeft();
                                }
                            }
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        // window.location.href = li + "manage-interviewer";
                    }
                });
            },
            error: function (data) {
                // alert("Something Went Wrong.!");
            },
        });
    });
    remote_select("js-data-example-ajax", 1);
});

function remote_select(cls, id) {
    $("." + cls).select2({
        minimumInputLength: 3,
        ajax: {
            url: "all-product",
            dataType: "json",
            type: "GET",
            quietMillis: 50,
            data: function (term) {
                return {
                    _token: $("meta[name=csrf-token]").attr("content"),
                    name: term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.product_name,
                            id: item.id,
                        };
                    }),
                };
            },
            transport: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            },
        },
    });
}

// get product wise price

const productwiseprice = (id, rowid) => {
    // console.log(id);
    // console.log(rowid);
    $.ajax({
        url: "product-wise-price/" + id,
        type: "GET",
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function (data) {
            // console.log(data.product_price);
            $("#price-" + rowid).val(data.product_price);
        },
    });
};

// get qty wise product price
const productQty = (value, rowid) => {
    let product_price = $("#price-" + rowid).val();
    let product_qty = $("#qty-" + rowid).val();
    if (typeof product_price === "undefined") {
        product_price = 0;
    }

    if (typeof product_qty === "undefined") {
        product_qty = 0;
    }
    let netAmount = product_qty * product_price;
    $("#hiddennet-" + rowid).val(netAmount);
    $("#net-" + rowid).val(netAmount);
};

// get discount //

const discount = (value, rowid) => {
    let netAmount = $("#hiddennet-" + rowid).val();
    let discount = $("#discount-" + rowid).val();
    if (typeof discount === "undefined") {
        discount = 0;
    }

    if (typeof netAmount === "undefined") {
        netAmount = 0;
    }

    let netdiscount = (netAmount * discount) / 100;
    let netTotal = netAmount - netdiscount;
    $("#net-" + rowid).val(netTotal);
    getTotalAmount(rowid);

};
let FinalAmount = [];
const getTotalAmount = (rowId)=>{
      //let FinalAmount = [];

    $('.netAmounts').each(function(index,val){
        if(typeof FinalAmount[rowId] === 'undefined') {
            FinalAmount.push(parseInt(val.value));
        }
        else {
            FinalAmount[rowId] = parseInt(val.value);
        }
    });
    calculateValue();
    // let result = FinalAmount.reduce(function (a, b) {
    //     return a + b;
    //   });

    //   $('#Totalvalue').val(result);
    //   console.log(result);
}


const calculateValue = ()=>{
    let result = FinalAmount.reduce(function (a, b) {
        return a + b;
      });

      $('#Totalvalue').val(result);
      console.log(result);
}


// get final discount ..........

const Finaldiscount = ()=>{
    let final_discount = $('#finalDiscount').val();
    let total_value = $('#Totalvalue').val();
    if(typeof final_discount ==='undefined'){
        final_discount = 0;
    }
    if(typeof total_value ==='undefined'){
        total_value = 0;
    }

    let discountTotal = total_value * final_discount / 100
    let finalResult = total_value - discountTotal
    $('#finalnetAmounts').val(finalResult);
}

function remove_row(id) {
    $("#div_" + id).remove();
    initial = 1;
    delete FinalAmount[id];
    calculateValue();
}

