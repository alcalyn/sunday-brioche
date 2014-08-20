jQuery(function () {
    enableSizeSelector();
    enableSliders();
    enableSelect2();
});

function enableSizeSelector() {
    jQuery('#size-tab a').click(function (e) {
        e.preventDefault();
        jQuery(this).tab('show');
        var size = jQuery(this).attr('href').split('-')[1];
        jQuery('input[name=size]').val(size);
    })
}

function enableSliders() {
    jQuery('#slider-butter').slider({
        formater: formaterButter
    });
    jQuery('#slider-sugar').slider({
        formater: formaterSugar
    });
}

function enableSelect2() {
    jQuery('.select2').select2({
        minimumResultsForSearch: -1
    });
}

function formaterButter(value) {
    return ['Moins beurée', 'Normal', 'Bien beurrée'][value];
}

function formaterSugar(value) {
    return ['Moins sucrée', 'Normal', 'Bien sucrée'][value];
}
