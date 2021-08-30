$(document).ready(function () {
  $(".start-game").click(function (e) {
    e.preventDefault();
    var fname = $("#fname").val();
    var email = $("#email").val();
    var number = $("#number").val();
    var code = $("#code").val();
    var tos = $(".rule-checkbox").prop("checked");
    var mailing = $(".mailing-checkbox").prop("checked");
    $.ajax({
      type: "POST",
      url: "submission.php",
      data: {
        fname: fname,
        email: email,
        number: number,
        code: code,
        tos: tos,
      },
      success: function (data) {
        if (data == "let the game begin " + code) {
          openGame(fname, email, number, code, mailing);
        } else if (data == "Please provide your full name") {
          dataInvalid("Please provide your full name");
        } else if (data == "Invalid email address") {
          dataInvalid("Invalid email address");
        } else if (data == "Invalid mobile number") {
          dataInvalid("Invalid mobile number");
        } else if (data == "Please confirm your agreement") {
          dataInvalid("Please confirm your agreement");
        } else if (data == "code is invalid") {
          openAlertUser();
        } else {
          alert(data);
        }
      },
    });
  });

  function openGame(fname, email, number, code, mailing) {
    $(".data-confirmation-field").addClass("display-none");
    $("#fnameId").val(fname);
    $("#emailId").val(email);
    $("#numberId").val(number);
    $("#codeId").val(code);
    $("#mailingId").val(mailing);
    $(".lottery").removeClass("display-none");
    window.scrollTo(0, 0);
  }

  function openAlertUser() {
    $(".alert-notify").removeClass("hiddenElement");
    $(".alert-notify").addClass("visibleElement");
  }

  $(".close-alert").click(function (e) {
    $(".alert-notify").removeClass("visibleElement");
    $(".alert-notify").addClass("hiddenElement");
  });

  function dataInvalid(string) {
    $(".user-warning").remove();
    $(".higlight-desc").append(
      '<div class="user-warning">' + string + "!" + "</div>"
    );
    $(".player-form").css("padding-bottom", "30px");
  }

  $(".game-box").click(function (e) {
    $(this).addClass("player-choise");
    $(".game-box").addClass("disabled");

    openDoubleCheck();
  });

  function openDoubleCheck() {
    $(".double-check-form").removeClass("hiddenElement");
    $(".double-check-form").addClass("visibleElement");
  }

  $(".choise").click(function (e) {
    $(".double-check-form").removeClass("visibleElement");
    $(".double-check-form").addClass("hiddenElement");
    var text = $(this).find(".choise-text").text();
    if (text.localeCompare("YES") == 0) {
      var fname = $("#fnameId").val();
      var email = $("#emailId").val();
      var number = $("#numberId").val();
      var code = $("#codeId").val();
      var mailing = $("#mailingId").val();
      var playerChoise = $(".player-choise").attr("id");
      $.ajax({
        type: "POST",
        url: "lottery.php",
        data: {
          fname: fname,
          email: email,
          number: number,
          code: code,
          playerChoise: playerChoise,
          mailing: mailing,
        },
        success: function (data) {
          var res = JSON.parse(data);
          openGameCell(playerChoise, res);
        },
      });
    } else {
      $(".game-box").removeClass("player-choise");
      $(".game-box").removeClass("disabled");
    }
  });

  function openGameCell(playerChoise, res) {
    var gameBoxes = $(".game-box");
    for (var i = 0; i < 9; i++) {
      if (gameBoxes[i].id == playerChoise) {
        $(gameBoxes[i]).removeClass("player-choise");
        if (res[9].localeCompare("true") == 0) {
          makeRevialedIcon(gameBoxes[i], "prize-logo", res[i][0], res[i][1]);
        } else {
          $(gameBoxes[i]).addClass("loose-logo");
        }
      }
    }
    if (res[9].localeCompare("true") == 0) {
      setTimeout(function () {
        openWinField(res, playerChoise);
      }, 1500);
    } else {
      setTimeout(function () {
        openLooseField(res, playerChoise);
      }, 1500);
    }
  }

  function hideBoard(res, playerChoise) {
    var gameBoxes = $(".game-box");
    for (var i = 0; i < 9; i++) {
      if (res[i][0].localeCompare("loose") != 0) {
        if (gameBoxes[i].id != playerChoise) {
          makeRevialedIcon(gameBoxes[i], "prize-logo", res[i][0], res[i][1]);
        }
      } else {
        $(gameBoxes[i]).addClass("loose-logo");
      }
    }
    $(".company-logo-wrapper").addClass("display-none");
    $(".game-field").addClass("display-none");
    $(".ts-cs-apply").addClass("display-none");
  }

  function makeRevialedIcon(gameBox, logoclass, iconName, price) {
    var prizeLogo = document.createElement("img");
    prizeLogo.src = "/img/prizes/" + iconName + ".png";
    prizeLogo.classList.add(logoclass);
    var prizePrice = document.createElement("div");
    prizePrice.classList.add("prize-price");
    prizePrice.classList.add("result-price");
    prizePrice.innerHTML = "R" + price;
    gameBox.appendChild(prizeLogo);
    gameBox.appendChild(prizePrice);
    $(gameBox).addClass("revialed");
  }

  function openWinField(res, playerChoise) {
    var roundBox = $(".prise-logo")[0];
    makeRevialedIcon(
      roundBox,
      "result-logo",
      res[playerChoise - 1][0],
      res[playerChoise - 1][1]
    );
    $(".win-field>.highlight-result")
      .last()
      .html(
        "You've won a R" +
        res[playerChoise - 1][1] +
        " " +
        getWinnerText(res[playerChoise - 1][0])
      );
    if (res[playerChoise - 1][0].localeCompare("cash") == 0) {
      $(".congrats-desc")
        .first()
        .html(
          "You’ll soon receive an email to confirm your cellphone number." +
          "<br>" +
          "This could take up to 24 hours and entries may need to be validated."
        );
    }
    $(".win-field").removeClass("display-none");
    hideBoard(res, playerChoise);
    inscreaseLotteryHeight();
  }

  function getWinnerText(picName) {
    if (picName.localeCompare("checkers") == 0) {
      return "Checkers grocery voucher";
    } else if (picName.localeCompare("netflix") == 0) {
      return "Netflix voucher";
    } else if (picName.localeCompare("takealot") == 0) {
      return "Takealot voucher";
    } else if (picName.localeCompare("uberEats") == 0) {
      return "Uber-eats voucher";
    } else if (picName.localeCompare("cash") == 0) {
      return "eWallet cash withdrawal";
    } else if (picName.localeCompare("sweep_south") == 0) {
      return "SweepSouth voucher";
    }
  }

  function openLooseField(res, playerChoise) {
    $(".loose-field").removeClass("display-none");
    hideBoard(res, playerChoise);
    inscreaseLotteryHeight();
  }

  $(".close-share-to").click(function (e) {
    $(".share-to-block").removeClass("visibleElement");
    $(".share-to-block").addClass("hiddenElement");
  });

  function inscreaseLotteryHeight() {
    if (screen.width < 400) {
      $(".lottery").height(750);
    }
  }

  $(".reveal-board").click(function (e) {
    $(this).parent().parent().addClass("display-none");
    revealBoard();
  });

  function revealBoard() {
    $(".game-title").html("GAME BOARD REVEAL");
    $(".game-sentence").first().html("Your game board has been played.");
    $(".game-sentence")
      .last()
      .html("Post another ad on Gumtree to play again.");
    $(".company-logo-wrapper").removeClass("display-none");
    $(".game-field").removeClass("display-none");
    $(".ts-cs-apply").removeClass("display-none");
    var goTo = document.createElement("a");
    goTo.classList.add("congrats-button");
    goTo.innerHTML = "GO TO GUMTREE";
    goTo.href = "https://www.gumtree.co.za/";

    goTo.target = "_blank";
    $(".game-grid").after(goTo);
    decreaseHeight();
  }

  function decreaseHeight() {
    if (screen.width < 400) {
      $(".lottery").css("height", "auto");
    }
  }

  $(".share-button").click(function (e) {
    $(".share-to-block").removeClass("hiddenElement");
    $(".share-to-block").addClass("visibleElement");
  });
});
