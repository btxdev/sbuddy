<style>
  html{
    --bg-baner: radial-gradient(ellipse at center, rgba(228,27,20,1) 0%, rgba(126,21,16,1) 100%);;
  }

  .tbEZnryv7sfPlz19JYP7{
    width: 600px;
    height: 450px;
    background: var(--bg-baner);
    overflow: hidden;
    position: relative;
  }
  .tbEZnryv7sfPlz19JYP7-box{
    position: absolute;
    height: 160px;
    width: 160px;
    background-image: url('media/external_module/newYear/box1.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    left: -20px;
    top: -24px;
    transform: rotate(60deg);
    z-index: 9;
  }
  .tbEZnryv7sfPlz19JYP7-box2{
    position: absolute;
    height: 160px;
    width: 160px;
    background-image: url('media/external_module/newYear/box2.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    right: -22px;
    top: 49px;
    bottom: 0;
    margin: auto;
    transform: translate(25%, 0px) rotate(60deg);
    z-index: 9;
  }
  .tbEZnryv7sfPlz19JYP7-bag{
    position: absolute;
    height: 160px;
    width: 160px;
    background-image: url('media/external_module/newYear/bag.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    right: -16px;
    bottom: -39px;
    margin: auto;
    transform: translate(0%, 0px) rotate(31deg);
    z-index: 9;
  }
  .tbEZnryv7sfPlz19JYP7-branch{
    position: absolute;
    height: 160px;
    width: 160px;
    background-image: url('media/external_module/newYear/branch1.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    left: 0px;
    right: 0;
    bottom: 0;
    margin: auto;
    transform: translate(0%, 50%) rotate(0deg);
    z-index: 9;
  }
  .tbEZnryv7sfPlz19JYP7-gift{
    position: absolute;
    height: 160px;
    width: 160px;
    background-image: url('media/external_module/newYear/gift.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    left: 0px;
    bottom: 0;
    transform: translate(-40%, 40%) rotate(15deg);
    z-index: 9;
  }
  .tbEZnryv7sfPlz19JYP7-cone{
    position: absolute;
    height: 60px;
    width: 60px;
    background-image: url('media/external_module/newYear/cone.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    z-index: 9;
  }
  .tbEZnryv7sfPlz19JYP7-card{
    position: absolute;
    height: 80px;
    width: 80px;
    background-image: url('media/external_module/newYear/card.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    z-index: 8;
    left: -73px;
    right: 0;
    margin: auto;
  }
  .tbEZnryv7sfPlz19JYP7-snowflake{
    position: absolute;
    height: 40px;
    width: 40px;
    background-image: url('media/external_module/newYear/snowflake.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    z-index: 1;
  }
  .tbEZnryv7sfPlz19JYP7-text{
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    width: 70%;
    height: 190px;
    text-align: center;
    font-size: 20px;
    font-family: pfl;
    color: var(--colorI);
  }
  .tbEZnryv7sfPlz19JYP7-text-title{
    font-family: pfb;
    font-size: 33px;
  }
  .tbEZnryv7sfPlz19JYP7-text-titleSmall2{
    margin-top: 10px;
    font-family: pfl;
    font-size: 18px;
  }
  .tbEZnryv7sfPlz19JYP7-text-btn{
    display: inline-block;
    padding: 10px 25px;
    background-color: #5d78ff;
    font-family: pfb;
    border-radius: 5px;
    margin-top: 40px;
    cursor: pointer;
    transition: 0.15s all;
    z-index: 10;
    position: relative;
    user-select: none;
  }
  .tbEZnryv7sfPlz19JYP7-text-btn:hover{
    background-color: #4d66e2;
  }
  .tbEZnryv7sfPlz19JYP7-text-btn:active{
    transform: scale(0.98);
  }

  @media only screen and (max-width: 600px)  {
    .tbEZnryv7sfPlz19JYP7{
      width: 100%;
    }
    .tbEZnryv7sfPlz19JYP7-box2{
      right: -50px;
    }
    .tbEZnryv7sfPlz19JYP7-box{
      left: -54px;
      top: -38px;
    }
    .tbEZnryv7sfPlz19JYP7-bag{
      right: -59px;
    }
    .tbEZnryv7sfPlz19JYP7-card{
      left: -54px;
      right: 0;
      top: 29px;
      margin: auto;
    }
  }

  @media only screen and (max-width: 836px)  {
    .tbEZnryv7sfPlz19JYP7{
      width: 100%;
    }
  }

</style>
<?php

  $newsYearHref = '';

  if(!$newYearPanel){
    $newsYearHref = '$(location).attr('."'".'href'."'".','."'".'#'."'".');';
  } else{
    $newsYearHref = 'open_window('."'".'#settings'."'".')';
  }

?>
<div class='window-zindex' id='saleNewYear' style="display: block; opacity: 1;">
  <div class='window-block'>
    <div class='to_close icon-close' style='background-color: transparent; color: var(--colorI);' title='Закрыть' onclick="close_window(this)"></div>
    <div class='tbEZnryv7sfPlz19JYP7'>
      <div class='tbEZnryv7sfPlz19JYP7-text'>
        <div class='tbEZnryv7sfPlz19JYP7-text-titleSmall'>С новым годом!</div>
        <div class='tbEZnryv7sfPlz19JYP7-text-title'>Новогоднее оформление</div>
        <div class='tbEZnryv7sfPlz19JYP7-text-titleSmall2'>Оформи свой сайт к новому году!</div>
        <div class='tbEZnryv7sfPlz19JYP7-text-btn' onclick="<?=$newsYearHref?>">Перейти</div>
      </div>
      <div class='tbEZnryv7sfPlz19JYP7-box'></div>
      <div class='tbEZnryv7sfPlz19JYP7-box2'></div>
      <div class='tbEZnryv7sfPlz19JYP7-branch'></div>
      <div class='tbEZnryv7sfPlz19JYP7-bag'></div>
      <div class='tbEZnryv7sfPlz19JYP7-gift'></div>
      <div class='tbEZnryv7sfPlz19JYP7-card'></div>
      <div class='tbEZnryv7sfPlz19JYP7-cone' style='transform: rotate(46deg); left: 16px; top: 310px;'></div>
      <div class='tbEZnryv7sfPlz19JYP7-cone' style='background-image: url("media/external_module/cone2.png"); transform: rotate(46deg); right: 16px; top: 57px;'></div>
      <div class='tbEZnryv7sfPlz19JYP7-snowflake' style='transform: rotate(20deg); left: 16px; top: 196px;'></div>
      <div class='tbEZnryv7sfPlz19JYP7-snowflake' style='transform: rotate(-14deg); right: 97px; top: 15px;'></div>
      <div class='tbEZnryv7sfPlz19JYP7-snowflake' style='transform: rotate(34deg); right: 103px; bottom: 30px;'></div>
      <div class='tbEZnryv7sfPlz19JYP7-snowflake' style='transform: rotate(58deg); left: 149px; bottom: 27px;'></div>
    </div>
  </div>
</div>
