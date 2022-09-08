/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// start the Stimulus application
// import "./bootstrap";

const $ = require("jquery");
global.$ = global.jQuery = $;
require("bootstrap");


$(() => {
  $("a.ajax").on("click", (evtClick) => {
    evtClick.preventDefault();
    var href = evtClick.target.getAttribute("href");
    console.log(href);
    $.ajax({
      url: href,
      dataType: "json",
      success: (data) => {
        $("#nombre").html(data);
        console.log(data);
      },
      error: (jqXHR, status, error) => {
        console.log("ERREUR AJAX", status, error);
      },
    });
  });

  $("#formSearch").on("submit", (evtSubmit) => {
    evtSubmit.preventDefault();
    $.ajax({
      url: evtSubmit.target.getAttribute("action"),
      data: "search=" + $("#formSearch #search").val(),
      dataType: "html",
      success: (data) => {
        $("#main").html(data);
      },
      error: (jqXHR,status, error) => {
        console.log("ERREUR AJAX" , status,error);
    }
    })
  });
});
