<?php
    include_once 'init.php';
    // create all tables if not exist
    initCodesBasics();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="http://declutterthon.co.za/img/Gumtree_Declutterthon_FavIcon.jpg" type="image/gif"
        sizes="70x70">
    <meta property="og:url" content="https://declutterthon.co.za/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Gumtree Declutterthon" />
    <meta property="og:description" content="Try your luck for a chance to win a share of R100K in instant prizes!" />
    <meta property="og:image" content="http://declutterthon.co.za/img/social_share.jpg" />

    <title>Gumtree - Declutterthon</title>

    <link href="css/style.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</head>

<body>
    <section class="data-confirmation-field">
        <form class="player-form">
            <img src="img/company-logo.png" alt="" class="company-logo">
            <div class="data-description">Try your luck on our game board for a chance to win a share of R100K in
                instant prizes.
                <div class="higlight-desc">Fill in your details below to play</div>
                <div class="email-label">Please use the same email address used when creating your Gumtree listing</div>
            </div>
            <div class="data-grid">
                <input type="text" class='input-field' id="fname" placeholder="Full Name">
                <input type="text" class='input-field' id="email" placeholder="Email address">
                <input type="text" class='input-field' id="number" placeholder="Contact number">
                <?php
                    if (isset($_GET['code']) && !empty($_GET['code'])) {
                        ?>
                <input type="text" class='input-field' id="code" placeholder="Unique code"
                    value="<?php echo $_GET['code'] ?>">
                <?php
                    } else {
                        ?>
                <input type="text" class='input-field' id="code" placeholder="Unique code">
                <?php
                    }
                ?>
                <input type="checkbox" class="rule-checkbox" id="happy" name="happy" value="false">
                <label for="happy" class="label-cheker">
                    <div class="cheker-text">I accept the <a href="https://blog.gumtree.co.za/declutterthon/"
                            target="_blank" class="click-here">Ts&amp;Cs</a></div>
                </label>
                <input type="checkbox" class="mailing-checkbox" id="sad" name="sad" value="false" checked="checked">
                <label for="sad" class="label-cheker">
                    <div class="cheker-text">I agree to further communications from Gumtree</div>
                </label>
            </div>
            <div class="no-code-desc">Don't have a unique code? Create an ad on Gumtree to enter. One ad = One play
            </div>
            <input type="submit" class="start-game" value="Play">
        </form>

        <div class="alert-notify">
            <a href="#" class="close-notify close-alert"><img src="img/logos/arrow.png" alt="" class="arrow-pic"></a>
            <div class="alert-content">
                <div class="alert-title">Your code is invalid.
                </div>
                <div class="alert-decs">Refer to your email to ensure you copied your code correctly.
                    Didn't receive an email? Create an ad on Gumtree to enter.</div>
                <div class="alert-tip">Remember one ad is worth one play.</div>
                <a href="https://www.gumtree.co.za/" class="go-to-gumtree">
                    <div class="go-to-wrapper"> GO TO GUMTREE</div>
                </a>
            </div>
        </div>
    </section>

    <section class="lottery display-none">
        <div class="company-logo-wrapper">
            <img src="img/company-logo.png" alt="" class="company-logo">
        </div>
        <div class="game-field">
            <div class="game-title">PICK A BOX</div>
            <div class="game-sentence">To find out if you've won. </div>
            <div class="game-sentence">Remember 1 Gumtree ad is worth 1 play.</div>
            <div class="game-grid">
                <div class="game-box game-box-details" id="1"></div>
                <div class="game-box game-box-details" id="2"></div>
                <div class="game-box game-box-details" id="3"></div>
                <div class="game-box game-box-details" id="4"></div>
                <div class="game-box game-box-details" id="5"></div>
                <div class="game-box game-box-details" id="6"></div>
                <div class="game-box game-box-details" id="7"></div>
                <div class="game-box game-box-details" id="8"></div>
                <div class="game-box game-box-details" id="9"></div>
                <input type="hidden" id="fnameId" name="fname">
                <input type="hidden" id="emailId" name="email">
                <input type="hidden" id="numberId" name="number">
                <input type="hidden" id="codeId" name="code">
                <input type="hidden" id="mailingId" name="mailing">
            </div>
        </div>
        <div class="ts-cs-apply">Ts &amp; Cs Apply.</div>

        <div class="player-form win-field result-form display-none">
            <img src="img/company-logo.png" alt="" class="company-logo">
            <div class="prise-logo"></div>
            <div class="highlight-result">
                Congratulations!
            </div>
            <div class="highlight-result">
            </div>
            <div class="congrats-desc">
                You'll soon receive an email with a voucher code to claim your prize.<br>
                This could take up to 24 hours and entries may need to be validated.
            </div>
            <div class="result-desc congrats-desc">
                Post another ad on Gumtree for a chance to play again.
            </div>
            <div class="result-buttons">
                <a href="#" class="reveal-board congrats-button">REVEAL BOARD</a>
                <a href="https://www.gumtree.co.za/" class="congrats-button">GO TO GUMTREE</a>
                <a href="#" class="congrats-button share-button"><img src="img/logos/share_icon.png" alt=""
                        class="share-icon">SHARE</a>
            </div>

            <div class="share-to-block hiddenElement">
                <a href="#" class="close-notify close-share-to"><img src="img/logos/arrow.png" alt=""
                        class="arrow-pic"></a>
                <div class="share-to-text">SHARE TO</div>
                <div class="share-to-icons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://declutterthon.co.za/" target="_blank"
                        class="share-to-link"><img src="img/logos/facebook-share.png" alt="" class="share-to-pic"></a>
                    <a href="https://twitter.com/share?text=Try your luck for a chance to win a share of R100K in instant prizes!&url=https://declutterthon.co.za/"
                        class="share-to-link" target="_blank"><img src="img/logos/twitter-share.png" alt=""
                            class="share-to-pic"></a>
                    <a href="https://api.whatsapp.com/send?text=Try your luck for a chance to win a share of R100K in instant prizes! https://declutterthon.co.za/"
                        data-action="share/whatsapp/share" class="share-to-link"><img src="img/logos/whatsapp-share.png"
                            alt="" class="share-to-pic"></a>
                </div>
            </div>

        </div>

        <div class="player-form loose-field result-form display-none">
            <img src="img/company-logo.png" alt="" class="company-logo">
            <img src="img/empty_prize.png" alt="" class="prise-logo">
            <div class="fail-wrapper">
                <div class="highlight-result">
                    Don't give up just yet.
                </div>
                <div class="result-desc">
                    Post another ad on Gumtree for a chance to play again.
                </div>
            </div>
            <div class="result-buttons">
                <a href="#" class="reveal-board failed-button">REVEAL BOARD</a>
                <a href="https://www.gumtree.co.za/?utm_medium=tv&utm_source=Gameboard&utm_campaign=Declutterthon"
                    target="_blank" class="failed-button">GO TO GUMTREE</a>
            </div>
        </div>

        <div class="double-check-form ">
            <div class="main-question">Are you sure you'd like to pick this box?</div>
            <div class="main-question-title">Once you pick this box you won't be able to undo it.</div>
            <div class="choises">
                <a href="#" class="choise">
                    <div class="choise-text">YES</div>
                </a>
                <a href="#" class="choise">
                    <div class="choise-text">NO</div>
                </a>
            </div>
        </div>
        <div class="full-screen-closer"></div>
    </section>
</body>

</html>