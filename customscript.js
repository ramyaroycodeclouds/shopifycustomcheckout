// Example starter JavaScript for disabling form submissions if there are invalid fields
/*(function() {
    'use strict'
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation')
        // Loop over them and prevent submission
        Array.prototype.filter.call(forms, function(form) {

            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault()
                    event.stopPropagation()
                } 
                 
               form.classList.add('was-validated')
            }, false)
        })
    }, false)
}());*/

(function () {
    'use strict'
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
 

                if (form.checkValidity() === false) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                else {
                     
                    const form = document.querySelector('form');
                    const formdata = new FormData(form);
                    const params = new URLSearchParams(window.location.search).toString();

                    // const paramsObj = Array.from(params.keys()).reduce(
                    // (acc, val) => ({ ...acc, [val]: params.get(val) }),
                    // {}
                    // );
                    //console.log(paramsObj);
                
                    document.getElementById("loader").classList.remove("hide");
                    document.getElementById("submit").focus();

 
                    document.getElementById("ss_errs").classList.remove("show");
                    document.getElementById("ss_errs").classList.add("hide");
                    document.getElementById("submit").disabled = true;
                    
                    setTimeout(() => {
                        submitform(formdata, params);
                    }, "900");
                }
                form.classList.add('was-validated')
            }, false)
        })
    }, false)
}());

function submitform(formdata, params) 
{
    var xhr = new XMLHttpRequest();
    xhr.overrideMimeType("application/json");
    xhr.onload = function () {
        document.getElementById("loader").classList.add("hide");
        let res = JSON.parse(xhr.responseText);
        if (res.status == false) {
            document.getElementById("submit").disabled = false;
            if (res.message.hasOwnProperty('month_year')) {
                document.getElementById("expmonth").value = '';
                document.getElementById("expyear").value = '';
            }
            if (res.message.hasOwnProperty('crm_error')) {
                if (res.message.crm_error.hasOwnProperty('error_message')) {
                    document.getElementById("ss_errs").innerHTML = res.message.crm_error.error_message;
                }
                else {
                    document.getElementById("ss_errs").innerHTML = "Try Another Card. Failed to process";
                }
                document.getElementById("ss_errs").classList.remove("hide");
                document.getElementById("ss_errs").classList.add("show");
            }
        }
        else if (res.status == true) {
            sessionStorage.setItem("order_id", res.data.response.order_id);
            sessionStorage.setItem("store_orderno", res.data.response.store_orderno);
            window.location.href = res.data.response.redirect;
        }
    }
    xhr.open("POST", "create_campaign.php?" + params, false);
    xhr.send(formdata);
}

function detectCardType(cardNumber) {
    const patterns = {
        visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
        mastercard: /^5[1-5][0-9]{14}$/,
    };
    for (const cardType in patterns) {
        if (patterns[cardType].test(cardNumber)) {
            return cardType;
        }
    }
    return "unknown";
}
document.getElementById("cc-number").addEventListener("blur", creditcardnumberfunction);

function creditcardnumberfunction() {
    let cardvalue = document.getElementById("cc-number").value;
    let cardType = detectCardType(cardvalue);

    if (cardType == 'unknown') {
        cardType = customcardsettings(cardvalue);
    }
    if (cardType != '') {
        document.getElementById('creditCardType').value = '' + cardType + '';
        document.getElementById("classcreditcardtype").style.display = "none";
    }
    else {
        document.getElementById('cc-number').value = '';
        document.getElementById('creditCardType').value = '';
    }
}

document.getElementById("creditCardType").addEventListener("change", creditcardtypefunction);
function creditcardtypefunction() {
    let cardvalue = document.getElementById("cc-number").value;
    let creditCardType = document.getElementById("creditCardType").value;
    if (cardvalue != '') {
        let cardType = detectCardType(cardvalue);
        if (cardType == 'unknown') {
            cardType = customcardsettings(cardvalue);
        }

        if (cardType !== creditCardType) {
            document.getElementById("classcreditcardtype").style.display = "block";
            document.getElementById('creditCardType').value = '';
        }
        else {
            document.getElementById("classcreditcardtype").style.display = "none";
        }
    }
}

function customcardsettings(cardvalue) {
    let card_set = `{"cardlist":
                    [{"cardno":"1444444444444440|visa"},
                     {"cardno":"0000000000000000|master"}
                    ]
                }`;
    let privatecardType = '';
    card_set = JSON.parse(card_set);
    for (var i = 0; i < card_set.cardlist.length; i++) {
        let predefindedcardset = card_set.cardlist[i].cardno.split("|");
        if (predefindedcardset[0] == cardvalue) {
            privatecardType = predefindedcardset[1];
            return privatecardType;
        }
        else {
            return privatecardType;
        }
    }
}