$('#taxes').change(function(){
var percent = ($(this).find(':selected').data('id'));
var buy_price = $('#buy_price').val();
var quantity = $('#quantity').val();
var totalval = Number(buy_price)*Number(quantity);
var percentval = (Number(percent)/100)*(totalval);
var totalcost = Number(totalval)+Number(percentval);
//Set
$('#total_tax').val(percentval);
$('#net_cost').val(totalcost);
});

$("#buy_price").keyup(function() {
// var bla = $('#buy_price').val();
// var qty = $('#quantity').val();
// //Set
// $('#net_cost').val(Number(bla)*Number(qty));
var percent = ($("#taxes").find(':selected').data('id'));
var buy_price = $('#buy_price').val();
var quantity = $('#quantity').val();
var totalval = Number(buy_price)*Number(quantity);
var percentval = (Number(percent)/100)*(totalval);
var totalcost = Number(totalval)+Number(percentval);
});