<?php
require_once 'header.php';
require 'require.php';
?>
<style>
    .hide {
        display: none !important;
    }

    .show {
        display: block !important;
    }

    .customspinner {
        height: 20px;
        width: 20px;
    }
</style>
</head>

<body>


    <div class="container">

        <div class="row">
            <div class="col-md-8 order-md-1">

                <form name="checkout_form" action="javascript:void(0);" class="needs-validation" novalidate="" enctype="multipart/form-data" method="POST">

                    <div class="row">
                        <input type="hidden" name="campaignId" value="<?= CONFIG['product_mapp']['products']['product_details'][0]['cid'] ?>" />
                        <input type="hidden" name="offer_id" value="<?= CONFIG['product_mapp']['offer_id'] ?>" />
                        <input type="hidden" name="product_id" value="<?= CONFIG['product_mapp']['products']['product_details'][0]['pid'] ?>" />

                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="" required="">
                            <div class="invalid-feedback"> Valid first name is required. </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="" required="">
                            <div class="invalid-feedback"> Valid last name is required. </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="" required="">
                        <div class="invalid-feedback"> Valid email is required. </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="" required="" data-error-message="Please enter a valid contact number!" data-min-length="10" data-max-length="10" onkeyup="javascript: this.value = this.value.replace(/[^0-9]/g,'');" maxlength="10" minlength="10" />
                        <div class="invalid-feedback"> Valid phone no is required. </div>
                    </div>

                    <div class="mb-3">
                        <label for="shippingAddress1">Address</label>
                        <input type="text" class="form-control" name="shippingAddress1" id="shippingAddress1" placeholder="" required="">
                        <div class="invalid-feedback"> Please enter your shipping address. </div>
                    </div>
                    <div class="mb-3">
                        <label for="shippingAddress2">Address 2 <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" name="shippingAddress2" id="shippingAddress2" placeholder="Apartment or suite">
                    </div>

                    <div class="mb-3">
                        <label for="shippingCity">Shipping City</label>
                        <input type="text" class="form-control" name="shippingCity" id="shippingCity" placeholder="" required="">
                        <div class="invalid-feedback"> Please enter your shipping city. </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="shippingCountry">Country</label>
                            <select class="form-control custom-select d-block w-100" name="shippingCountry" id="shippingCountry" required="">
                                <option value="">Choose...</option>
                                <option selected value="US">United States</option>
                            </select>
                            <div class="invalid-feedback"> Please select a valid country. </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?php
                            $countrylist = file_get_contents('countries_states.json');
                            $countrylist = json_decode($countrylist);
                            $statelist = "";

                            for ($i = 0; $i < count($countrylist); $i++) {
                                if ($countrylist[0]->iso2 == 'US') {
                                    $statelist = $countrylist[0]->states;
                                }
                            }
                            ?>

                            <label for="shippingState">State</label>
                            <select name="shippingState" required="" id="shippingState" class="form-control custom-select d-block w-100" data-field="state" data-group="1" id="shippingState" data-default="" data-error-message="Please select your state!">
                                <option value="" selected="selected">Select State</option>
                                <?php
                                for ($j = 0; $j < count($statelist); $j++) {
                                ?>
                                    <option value="<?= $statelist[$j]->state_code ?>"><?= $statelist[$j]->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback"> Please select a valid state. </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="shippingZip">Zip</label>
                            <input type="tel" class="form-control custom-select d-block w-100" required="" id="shippingZip" name="shippingZip" placeholder="Zip code" maxlength="5" data-min-length="5" data-max-length="5" onkeyup="javascript: this.value = this.value.replace(/[^0-9]/g,'');" data-error-message="Please enter a valid Zip code!">
                            <div class="invalid-feedback"> Zip code required. </div>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="billingSameAsShipping" value="yes">
                    </div>

                    <hr class="mb-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-name">Name on card</label>
                            <input type="text" class="form-control" id="cc-name" placeholder="" required="">
                            <small class="text-muted">Full name as displayed on card</small>
                            <div class="invalid-feedback"> Name on card is required </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label><strong>Select Card Type:</strong></label>
                                <select name="creditCardType" class="required no-error form-control" id="creditCardType" data-error-message="Please select a valid card type">
                                    <option value="">Card Type</option>
                                    <option value="master">Master Card</option>
                                    <option value="visa">Visa</option>
                                </select>
                            </div>
                            <div class="invalid-feedback" id="classcreditcardtype"> Select valid credit card type </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="cc-number">Credit card number</label>
                            <input type="text" name="creditCardNumber" class="form-control" id="cc-number" required="" maxlength="16" data-error-message="Please enter a valid credit card number!" placeholder="Enter Your Card No" onkeyup="javascript: this.value = this.value.replace(/[^0-9]/g, '');">
                            <div class="invalid-feedback"> Credit card number is required </div>
                        </div>
                    </div>

                    <?php
                    $start    = new DateTime();
                    $end      = new DateTime('2025-12-01');
                    $interval = DateInterval::createFromDateString('1 month');
                    $period   = new DatePeriod($start, $interval, $end);
                    ?>

                    <div class="row">
                        <div class="col-sm-6 month">
                            <select name="expmonth" required="" class="required selcet-fld form-control" id="expmonth" data-error-message="Please select a valid expiry month!">
                                <option value="">Month</option>
                                <option value="01">(01) January</option>
                                <option value="02">(02) February</option>
                                <option value="03">(03) March</option>
                                <option value="04">(04) April</option>
                                <option value="05">(05) May</option>
                                <option value="06">(06) June</option>
                                <option value="07">(07) July</option>
                                <option value="08">(08) August</option>
                                <option value="09">(09) September</option>
                                <option value="10">(10) October</option>
                                <option value="11">(11) November</option>
                                <option value="12">(12) December</option>
                            </select>
                            <div class="invalid-feedback month">Select Valid Month</div>
                        </div>
                        <div class="col-sm-6 year">
                            <select name="expyear" required="" class="required selcet-fld form-control" id="expyear" data-error-message="Please select a valid expiry year!">
                                <option value="">Year</option>
                                <option value="24">2024</option>
                                <option value="25">2025</option>
                                <option value="26">2026</option>
                                <option value="27">2027</option>
                                <option value="28">2028</option>
                                <option value="29">2029</option>
                                <option value="30">2030</option>
                                <option value="31">2031</option>
                                <option value="32">2032</option>
                                <option value="33">2033</option>
                                <option value="34">2034</option>
                                <option value="35">2035</option>
                                <option value="36">2036</option>
                                <option value="37">2037</option>
                                <option value="38">2038</option>
                                <option value="39">2039</option>
                                <option value="40">2040</option>
                                <option value="41">2041</option>
                                <option value="42">2042</option>
                                <option value="43">2043</option>
                            </select>
                            <div class="invalid-feedback">Select Valid Year</div>
                        </div>
                        <div class="invalid-feedback" id="month_year"></div>
                    </div>
                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label for="cc-cvv">CVV</label>
                            <input type="tel" name="CVV" class="required form-control neumeric" required="" placeholder="cvv" data-validate="cvv" maxlength="3" data-error-message="Please enter a valid CVV code!" onkeyup="javascript: this.value = this.value.replace(/[^0-9]/g, '');">
                            <div class="invalid-feedback"> Security code required </div>
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <div class="invalid-feedback mb-4 hide" id="ss_errs"></div>
                    </div>
                    <button id="submit" class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout
                        <div class="spinner-grow text-light customspinner hide" id="loader" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="customscript.js"></script>


</body>

</html>