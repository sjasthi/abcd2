<!DOCTYPE html>
<?php
include('header.php'); 
?>
<html>
  <head>
    <title>PowerPoint Options</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
       html, body {
      min-height: 50%;
      color: white;
      }
      
      body, div, form, textarea, label { 
      padding: 0;
      margin: 0;
      outline: none;
      font-family: Roboto, Arial, sans-serif;
      font-size: 14px;
      color: white;
      line-height: 22px;
      }
      
      .quantity {
        color:black;
      }
     
      h1 {
      position: absolute;
      margin: 0;
      line-height: 45px;
      font-size: 45px;
      color: white;
      z-index: 2;
      }
      
      .testbox {
      display: flex;
      justify-content: center;
      align-items: center;
      height: inherit;
      padding: 20px;
      }
     
      .dialog {
      justify-content: left;
      align-items: left;
      height: inherit;
      padding: 20px;
      font-size: 20px;
      }
      
      .form2 {
      width: 40%;
      padding: 20px;
      border-radius: 6px;
      background: #2a3843;
      }
      
      .banner {
      position: relative;
      height: 200px;
      background-image: url("/uploads/media/default/0001/02/4e42b3735b93106f8167f9cfe092931dba56df55.jpeg");  
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      }
      
      .banner::after {
      content: "";
      background-color: #1ab189; 
      position: absolute;
      width: 100%;
      height: 100%;
      }
      
      input,  textarea {
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      } 
      
      input {
        padding: 5px;
      }
      
      textarea {
      width: calc(100% - 12px);
      padding: 5px;
      }
      .item:hover, .question label:hover, input:hover::placeholder {
      color:  #b5b5b5;
      }
      .item input:hover,  .item textarea:hover {
      border: 1px solid transparent;
      box-shadow: 0 0 3px 0  #aa1409;
      color:  #1ab189;
      }
      .item {
      position: relative;
      margin: 10px 0;
      }
      input[type=radio]  {
      display: none;
      }
      label.radio {
      position: relative;
      display: inline-block;
      margin: 5px 20px 15px 0;
      cursor: pointer;
      }
      .red {
      color: #1ab189;
      }
      .question span {
      margin-left: 30px;
      }
      label.radio:before {
      content: "";
      position: absolute;
      left: 0;
      width: 17px;
      height: 17px;
      border-radius: 50%;
      border: 2px solid #ccc;
      }
      input[type=radio]:checked + label:before, label.radio:hover:before {
      border: 2px solid #1ab189;
    }
      label.radio:after {
      content: "";
      position: absolute;
      top: 6px;
      left: 5px;
      width: 8px;
      height: 4px;
      border: 3px solid #aa1409;
      border-top: none;
      border-right: none;
      transform: rotate(-45deg);
      opacity: 0;
      }
      input[type=radio]:checked + label:after {
      opacity: 1;
      }
      .btn-block {
      margin-top: 10px;
      text-align: center;
      }
      .button2 {
      width: 150px;
      padding: 10px;
      border: none;
      border-radius: 5px; 
      background: #1ab189;
      font-size: 16px;
      color: #fff;
      cursor: pointer;
      }
      .button2:hover {
      background: #12785d;
      }
      @media (min-width: 568px) {}
      .name-item, .city-item {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      }
      .name-item input, .name-item div {
      width: calc(50% - 20px);
      }

      
    </style>
  </head>
  <body>
    <div class="testbox">
    <form class="form form2" method="POST" action="export_powerpoint.php">  
        <div class="banner">
          <h1>PowerPoint Options</h1>
        </div>
        <div class = "dialog"><b>Query Dialog: </b></div>
        <div class="quantity">
          <label>Number of Pages to Export (1 - 100)</label>
          <div class="question-answer">
            <div>
            <input type="number" id="quantity" name="quantity" min="1" max="100">
            </div>
          </div>
        </div>

        <div class = "dialog"><b>Display Options: </b></div>

        <div class="question">
          <label><b>Page Format Option</b></label>
          <div class="question-answer">
            <div>
              <input type="radio" value="1" id="radio_1" name="option2"/>
              <label for="radio_1" class="radio"><span>Images on odd pages, Text on even pages</span></label>
            </div>
            <div>
              <input type="radio" value="2" id="radio_2" name="option2"/>
              <label for="radio_2" class="radio"><span>Images on even pages, Text on odd pages</span></label>
            </div>
          </div>
        </div>
        <div class="btn-block">
          <button class="button button2" type="submit" href="export_powerpoint.php">Export</button>
        </div>
      </form>
    </div>
  </body>
</html>