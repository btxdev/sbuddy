<?php
  $deDaXzgeYUpqHVSER = idGenerator(20,5);
  $d52bK8JIXP0zAF2MUVuZ = idGenerator(20,5);

  $d3m5fbauSlhmRSBH8G0l = 'id-image-profile'; // image_profile
  $d2aIadte1D2Ax2vHKNJ3 = 'id-login-profile'; // login_profile
  $d6BRu4Xw0rEsfYJ2Gpjr = 'id-name-profile'; // name_profile
  $dNINGWRNMhHqhkubcFGS = 'id-name1-profile'; // name1_profile
  $dA9dD7OF8qO5hncuiEIp = 'id-name2-profile'; // name2_profile
  $dg6iAiKFQPFYNsTacdtj = 'id-email-profile'; // email_profile
  $dWqJfXMTRD5eusfOiUUK = 'id-tel-profile'; // tel_profile
  $dEhs7jdb2CKXxSEwjAbN = 'id-country-profile'; // country_profile
  $dFQp1gRHKYYewar6HGou = 'id-city-profile'; // city_profile
  $dVBpjqFzm7WP6PVrpJ0a = 'id-male-profile'; // male_profile
  $dhxaAHDLponKHoiCHO76 = 'id-female-profile'; // female_profile
  $dXsSETKQoSjTYrdiOpZw = 'id-oldpass-profile'; // old_password-change
  $dW4wBDTrHJqD6CjwVZEh = 'id-newpass1-profile'; // new_password-change
  $dmHRzI9aajOqX5KCAsz7 = 'id-newpass2-profile'; // new1_password-change
  $dZl1qWsxYcEncXDhndza = 'id-code-email-profile'; // new1_password-change
  $dFQp1gRHKYYewar6H5пЕ = 'id-rename'; // file/folder rename

  $maxTestStages = 4;

?>
<script>
  var Login;
  var maxTestStages = <?=$maxTestStages;?>;
  $(document).ready(function() {
    Login = {
      login: $('#<?=$deDaXzgeYUpqHVSER;?>'),
      password: $('#<?=$d52bK8JIXP0zAF2MUVuZ;?>')
    };
  });
</script> <?php //if(isset($userData['account']) && ($userData['account'] != 'poma098')):?>
<window <?php //if(isset($userData['account']) && ($userData['account'] != 'poma098')):?>style='opacity: 0; display: none;'<?php //endif; ?>> <!-- Чтоб ключить отображение окн (opacity: 1; display: block;) -->
                                            <!-- Чтоб ключить конкретное окно (width: 400px; overflow-x: hidden; opacity: 1; display: block; border-radius: 0px; transform: translate(-50%, -50%) scale(1);) -->
                                            <!-- 1 элемент с пустым id это заготовка -->

  <div class='window' id='' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title'>Заголовок</div>
      <div class='window-container-text'>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna
        aliqua. Ut enim ad minim veniam, quis nostrud exercitation
        ullamco laboris nisi ut aliquip ex ea commodo consequat.
        Duis aute irure dolor in reprehenderit in voluptate velit
        esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
        occaecat cupidatat non proident, sunt in culpa qui officia
        deserunt mollit anim id est laborum.
      </div>
      <input type='button' class='window-btn' value='Ок'>
    </div>
  </div>


  <div class='window' id='learning-online' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title' style='word-break: initial; max-width: calc(100% - 0px); text-align: center;' id='learning-window-title'>Запись на онлайн обучение</div>
      <div class='window-container-text'>
        <div class='window-container-text-learning' id='learning-window-reg-block'>
          <div class='window-container-text-learning-ico icons-book'></div>
          <div class='window-container-text-learning-text'>
            <div class='window-container-text-learning-text-title'>Вы записаны!</div>
            <div class='window-container-text-learning-text-main'>Ваша группа <b style='font-family: pfr;'>ГК-11</b><br>Следующее занятие в понедельник <i>(12.06.2020г)</i>.</div>
          </div>
        </div>
        <div class='window-container-text-text'>Выберите удобное для вас время занятий</div>
        <div class='window-container-text-main'>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-0' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-0" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-0">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-0"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Понедельник</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day0-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-1' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-1" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-1">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-1"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Вторник</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day1-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-2' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-2" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-2">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-2"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Среда</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day2-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-3' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-3" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-3">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-3"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Четверг</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day3-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-4' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-4" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-4">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-4"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Пятница</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day4-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-5' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-5" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-5">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-5"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Суббота</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day5-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-container-text-main-elem'>
            <label for='id-learning-online-6' class='window-container-text-main-elem-title'>
              <div class='window-container-text-main-elem-title-ch'>
                <input type="checkbox" class="window-container-text-main-elem-title-ch-learning-online checkbox-checked" id="id-learning-online-6" style="display: none;">
                <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-learning-online-6">
        					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-learning-online-6"></label>
        				</label>
              </div>
              <div class='window-container-text-main-elem-title-title'>Воскресенье</div>
            </label>
            <div class='window-container-text-main-elem-main'>
              <div class='window-container-text-main-elem-main-main'>
                <span class='window-container-text-main-elem-main-main-time-span'>
                  <div class='window-container-text-main-elem-main-main-time'>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span>-</span>
                    <input class='window-container-text-main-elem-main-main-time-input' required type="time">
                    <span class='window-container-text-main-elem-main-main-time-del icons-plus' title=''></span>
                  </div>
                </span>
                <div>
                  <div class='window-container-text-main-elem-main-main-btn'>
                    <div class='window-container-text-main-elem-main-main-btn-ico icons-plus'></div>
                    <div class='window-container-text-main-elem-main-main-btn-text' id='learning-window-day6-btn-add'>Добавить время</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div style='text-align: center; margin-top: 40px;'>
        <input type='button' class='window-btn center' style="cursor: default; margin-bottom: 25px; z-index: 0; position: relative; width: 160px; padding-top: 6px; padding-bottom: 6px;" value='Записаться' id='learning-window-btn-save'>
      </div>
    </div>
  </div>

  <div class='window' id='window-rename' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title' style='text-align: center; max-width: 100%; margin-top: 30px;'>Переименовать</div>



      <div class='phone-class-margin'>
        <label class='input' for="<?=$dFQp1gRHKYYewar6H5пЕ;?>">
          <div class='input-div'>
            <label for="<?=$dFQp1gRHKYYewar6H5пЕ;?>" class='input-icons icons-rename'></label>
            <input type="tel" class='input-input' required id='<?=$dFQp1gRHKYYewar6H5пЕ;?>'>
            <label for="<?=$dFQp1gRHKYYewar6H5пЕ;?>" class='input-placeholder'>Старое имя</label>
            <label for="<?=$dFQp1gRHKYYewar6H5пЕ;?>" class='input-line'>
              <label for="<?=$dFQp1gRHKYYewar6H5пЕ;?>" class='input-line-main0'></label>
              <label for="<?=$dFQp1gRHKYYewar6H5пЕ;?>" class='input-line-main'></label>
              <label for="<?=$dFQp1gRHKYYewar6H5пЕ;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>


      <div class='login-btn-login'>
        <!-- Когда поле изменили, то кнопка сохранить активная (window-btn) и имеет свойство (cursor: pointer), если нет, о имеет класс (window-btn-noactive) и свойство (cursor: default) -->
        <input type='button' onclick="Finder.rename.accept();" id="drive-rename-btn" class='window-btn' style='cursor: default; z-index: 0; position: relative; width: 160px; padding-top: 6px; padding-bottom: 6px;' value='Сохранить'>
      </div>
    </div>
  </div>
  <div class='window' id='control-test' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class="window-container-preloader2" style=' height: 33px; width: 200.766px; left: 25px; bottom: 25px; margin: auto; visibility: hidden; background-color: #2a9fd0; top: initial; right: initial;'></div>
    <div class='window-container'>
      <div class='window-container-title' id='test-title' style='word-break: initial; text-align: center;'>Узнай свой уровень английского языка!</div>
      <div class='window-container-title2' style='visibility: hidden;' id='control-test-stage-container'>
        <span id='control-test-stage'>1</span>
        вопрос из <?=$maxTestStages;?>
      </div>
      <div class='window-container-textOverflow'>
        <div class='window-container-textOverflow-elem'>
          <div class='window-container-textOverflow-elem-ico' style='height: 161px; background-image: url("media/svg/analyze.svg")'></div>
          <div class='window-container-textOverflow-elem-text2'></div>
          <br>
          <div class='window-container-textOverflow-elem-text3'>Выберите уровень сложности!</div>
          <br>
          <input type='button' style='left: 0; right: 0; margin: auto; display: block; margin-bottom: 10px; width: 100%;' class='window-btn' onclick="controlTest('next')" value='KIDS'>
          <input type='button' style='left: 0; right: 0; margin: auto; display: block; margin-bottom: 10px; width: 100%;' class='window-btn' onclick="IndexTest.open(1);" value='TEENS'>
          <input type='button' style='left: 0; right: 0; margin: auto; display: block; margin-bottom: 10px; width: 100%;' class='window-btn' onclick="controlTest('next')" value='ADULTS'>
        </div>
        <div class='window-container-textOverflow-elem'>

          <div class='window-container-textOverflow-elem-text' style='font-family: pfr; margin-bottom: 20px;'>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?
            <br>1 ответ
          </div>

          <label class="checkbox" for="id-test-ch1" style='margin-bottom: 20px; display: block;'>
    				<input type="radio" name="gender-profile" checked="" class="checkbox-checked" id="id-test-ch1" style="display: none;">
    				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-test-ch1">
    					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-test-ch1"></label>
    				</label>
    				<label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="id-test-ch1">Ответ 1</label>
    			</label>

          <label class="checkbox" for="id-test-ch2" style='margin-bottom: 20px; display: block;'>
            <input type="radio" name="gender-profile" checked="" class="checkbox-checked" id="id-test-ch2" style="display: none;">
            <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-test-ch2">
              <label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-test-ch2"></label>
            </label>
            <label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="id-test-ch2">Ответ 2</label>
          </label>

          <label class="checkbox" for="id-test-ch3" style='margin-bottom: 20px; display: block;'>
            <input type="radio" name="gender-profile" checked="" class="checkbox-checked" id="id-test-ch3" style="display: none;">
            <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-test-ch3">
              <label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-test-ch3"></label>
            </label>
            <label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="id-test-ch3">Ответ 3</label>
          </label>

        </div>
        <div class='window-container-textOverflow-elem'>

          <div class='window-container-textOverflow-elem-text' style='font-family: pfr; margin-bottom: 20px;'>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?
            <br>Ответ в виде текста
          </div>

          <div style="width: calc(100% - 0px);">
            <label class="input" for="test-string">
              <div class="input-div">
                <label for="test-string" class="input-icons icons-check"></label>
                <input type="text" class="input-input" required="" id="test-string">
                <label for="test-string" class="input-placeholder">Ответ</label>
                <label for="test-string" class="input-line">
                  <label for="test-string" class="input-line-main0"></label>
                  <label for="test-string" class="input-line-main"></label>
                  <label for="test-string" class="input-line-main2"></label>
                </label>
              </div>
            </label>
          </div>
        </div>
        <div class='window-container-textOverflow-elem'>

          <div class='window-container-textOverflow-elem-text' style='font-family: pfr; margin-bottom: 20px;'>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?
            <br>Множество ответов
          </div>

          <label class="checkbox" for="id-test-ch11" style='margin-bottom: 20px; display: block;'>
    				<input type="checkbox" name="test-ch11" class="checkbox-checked" id="id-test-ch11" style="display: none;">
    				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-test-ch11">
    					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-test-ch11"></label>
    				</label>
    				<label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="id-test-ch11">Ответ 1</label>
    			</label>

          <label class="checkbox" for="id-test-ch22" style='margin-bottom: 20px; display: block;'>
            <input type="checkbox" name="test-ch1"  class="checkbox-checked" id="id-test-ch22" style="display: none;">
            <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-test-ch22">
              <label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-test-ch22"></label>
            </label>
            <label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="id-test-ch22">Ответ 2</label>
          </label>

          <label class="checkbox" for="id-test-ch33" style='margin-bottom: 20px; display: block;'>
            <input type="checkbox" name="test-ch1"  class="checkbox-checked" id="id-test-ch33" style="display: none;">
            <label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="id-test-ch33">
              <label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="id-test-ch33"></label>
            </label>
            <label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="id-test-ch33">Ответ 3</label>
          </label>

        </div>
        <div class='window-container-textOverflow-elem'>
          <div class='window-container-textOverflow-elem-ico' style='height: 161px; margin-bottom: 25px;'></div>
          <div class='window-container-textOverflow-elem-text2'>Ваш уровень английского языка на высоком уровне!</div>
          <br>
          <div class='window-container-textOverflow-elem-text3' style='font-size: 20px;'>Имя (если чел зареган если нет то просто "Вы ..."), вы набрали <b>70</b> баллов из 70</div>
          <br>
        </div>
      </div>
      <input type='button' id='btn-test' style='display: none;' class='window-btn' onclick="IndexTest.next();" value='Следующий вопрос'>
    </div>
    <div class='test-preloader'>
      <div class='login-preloader-elem1'></div>
      <div class='login-preloader-elem2'></div>
      <div class='login-preloader-elem3'></div>
      <div class='login-preloader-elem11'></div>
      <div class='login-preloader-elem22'></div>
      <div class='login-preloader-elem33'></div>
      <div class='login-preloader-elem4'></div>
    </div>
  </div>
  <div class='window' id='email-code' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title' style='text-align: center; max-width: 100%; margin-top: 30px;'>Смена почты</div>

      <div style='width: calc(80% - 30px); margin-left: 50px;'>
        <label class='input' for="<?=$dZl1qWsxYcEncXDhndza;?>">
          <div class='input-div'>
            <label for="<?=$dZl1qWsxYcEncXDhndza;?>" class='input-icons icons-shield'></label>
            <input type="code" class='input-input' value="" required id='<?=$dZl1qWsxYcEncXDhndza;?>'>
            <label for="<?=$dZl1qWsxYcEncXDhndza;?>" class='input-placeholder'>Код подтверждения</label>
            <label for="<?=$dZl1qWsxYcEncXDhndza;?>" class='input-line'>
              <label for="<?=$dZl1qWsxYcEncXDhndza;?>" class='input-line-main0'></label>
              <label for="<?=$dZl1qWsxYcEncXDhndza;?>" class='input-line-main'></label>
              <label for="<?=$dZl1qWsxYcEncXDhndza;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>


      <div class='login-btn-login'>
        <!-- Когда поле изменили, то кнопка сохранить активная (window-btn) и имеет свойство (cursor: pointer), если нет, о имеет класс (window-btn-noactive) и свойство (cursor: default) -->
        <input type='button' onclick="ProfileForm.code.send();" class='window-btn' style='cursor: default; z-index: 0; position: relative; width: 160px; padding-top: 6px; padding-bottom: 6px;' value='Сохранить'>
      </div>
    </div>
  </div>
  <div class='window' id='password-change' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title' style='text-align: center; max-width: 100%; margin-top: 30px;'>Смена пароля</div>

      <div style='width: calc(80% - 30px); margin-left: 50px;'>
        <label class='input' for="<?=$dXsSETKQoSjTYrdiOpZw;?>">
          <div class='input-div'>
            <label for="<?=$dXsSETKQoSjTYrdiOpZw;?>" class='input-icons icons-lock'></label>
            <input type="password" class='input-input' value="" required id='<?=$dXsSETKQoSjTYrdiOpZw;?>'>
            <label for="<?=$dXsSETKQoSjTYrdiOpZw;?>" class='input-placeholder'>Старый пароль</label>
            <label for="<?=$dXsSETKQoSjTYrdiOpZw;?>" class='input-line'>
              <label for="<?=$dXsSETKQoSjTYrdiOpZw;?>" class='input-line-main0'></label>
              <label for="<?=$dXsSETKQoSjTYrdiOpZw;?>" class='input-line-main'></label>
              <label for="<?=$dXsSETKQoSjTYrdiOpZw;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div style='width: calc(80% - 30px); margin-left: 50px;'>
        <label class='input' for="<?=$dW4wBDTrHJqD6CjwVZEh;?>">
          <div class='input-div'>
            <label for="<?=$dW4wBDTrHJqD6CjwVZEh;?>" class='input-icons icons-lock'></label>
            <input type="password" class='input-input' value="" required id='<?=$dW4wBDTrHJqD6CjwVZEh;?>'>
            <label for="<?=$dW4wBDTrHJqD6CjwVZEh;?>" class='input-placeholder'>Новый пароль</label>
            <label for="<?=$dW4wBDTrHJqD6CjwVZEh;?>" class='input-line'>
              <label for="<?=$dW4wBDTrHJqD6CjwVZEh;?>" class='input-line-main0'></label>
              <label for="<?=$dW4wBDTrHJqD6CjwVZEh;?>" class='input-line-main'></label>
              <label for="<?=$dW4wBDTrHJqD6CjwVZEh;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div style='width: calc(80% - 30px); margin-left: 50px; margin-bottom: 25px;'>
        <label class='input' for="<?=$dmHRzI9aajOqX5KCAsz7;?>">
          <div class='input-div'>
            <label for="<?=$dmHRzI9aajOqX5KCAsz7;?>" class='input-icons icons-lock'></label>
            <input type="password" class='input-input' value="" required id='<?=$dmHRzI9aajOqX5KCAsz7;?>'>
            <label for="<?=$dmHRzI9aajOqX5KCAsz7;?>" class='input-placeholder'>Новый пароль</label>
            <label for="<?=$dmHRzI9aajOqX5KCAsz7;?>" class='input-line'>
              <label for="<?=$dmHRzI9aajOqX5KCAsz7;?>" class='input-line-main0'></label>
              <label for="<?=$dmHRzI9aajOqX5KCAsz7;?>" class='input-line-main'></label>
              <label for="<?=$dmHRzI9aajOqX5KCAsz7;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='login-btn-login'>
        <!-- Когда поле изменили, то кнопка сохранить активная (window-btn) и имеет свойство (cursor: pointer), если нет, о имеет класс (window-btn-noactive) и свойство (cursor: default) -->
        <input type='button' onclick="ProfileForm.password.change();" id="profile-form-save-btn-1" class='window-btn-noactive' style='cursor: default; z-index: 0; position: relative; width: 160px; padding-top: 6px; padding-bottom: 6px;' value='Сохранить'>
      </div>
    </div>
  </div>
  <div class='window' id='profile' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title' style='text-align: center; max-width: 100%; margin-top: 30px;'>Профиль</div>
      <input id='<?=$d3m5fbauSlhmRSBH8G0l?>' type="file" style='display: none;'>
      <label for='<?=$d3m5fbauSlhmRSBH8G0l?>' id='id-image-block' title='Изменить изображение' class='window-container-image icons-imgAdd' style='margin-top: 25px; background-image: url(&quot;<?=$userData["profile_icon"]?>&quot;);'></label>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>">
          <div class='input-div'>
            <label for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>" class='input-icons icons-user'></label>
            <input type="text" class='input-input' disabled value="<?=$userData['account']?>" required id='<?=$$d2aIadte1D2Ax2vHKNJ3;?>'>
            <label for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>" class='input-placeholder'>Логин</label>
            <label for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>" class='input-line' style='background-color: var(--colorMainYellow);'>
              <label for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>" class='input-line-main0' style='background-color: var(--colorMainYellow);'></label>
              <label for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>" class='input-line-main' style='background-color: var(--colorMainYellow);'></label>
              <label for="<?=$$d2aIadte1D2Ax2vHKNJ3;?>" class='input-line-main2' style='background-color: var(--colorMainYellow);'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>">
          <div class='input-div'>
            <label for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>" class='input-icons icons-user-checked'></label>
            <input type="text" class='input-input' value="<?=$userData['name1']?>" required id='<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>'>
            <label for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>" class='input-placeholder'>Имя</label>
            <label for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>" class='input-line'>
              <label for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>" class='input-line-main0'></label>
              <label for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>" class='input-line-main'></label>
              <label for="<?=$d6BRu4Xw0rEsfYJ2Gpjr;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$dNINGWRNMhHqhkubcFGS;?>">
          <div class='input-div'>
            <label for="<?=$dNINGWRNMhHqhkubcFGS;?>" class='input-icons icons-user-checked'></label>
            <input type="text" class='input-input' value="<?=$userData['name2']?>" required id='<?=$dNINGWRNMhHqhkubcFGS;?>'>
            <label for="<?=$dNINGWRNMhHqhkubcFGS;?>" class='input-placeholder'>Фамилия</label>
            <label for="<?=$dNINGWRNMhHqhkubcFGS;?>" class='input-line'>
              <label for="<?=$dNINGWRNMhHqhkubcFGS;?>" class='input-line-main0'></label>
              <label for="<?=$dNINGWRNMhHqhkubcFGS;?>" class='input-line-main'></label>
              <label for="<?=$dNINGWRNMhHqhkubcFGS;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$dA9dD7OF8qO5hncuiEIp;?>">
          <div class='input-div'>
            <label for="<?=$dA9dD7OF8qO5hncuiEIp;?>" class='input-icons icons-user-checked'></label>
            <input type="text" class='input-input' value="<?=$userData['name3']?>" required id='<?=$dA9dD7OF8qO5hncuiEIp;?>'>
            <label for="<?=$dA9dD7OF8qO5hncuiEIp;?>" class='input-placeholder'>Отчество</label>
            <label for="<?=$dA9dD7OF8qO5hncuiEIp;?>" class='input-line'>
              <label for="<?=$dA9dD7OF8qO5hncuiEIp;?>" class='input-line-main0'></label>
              <label for="<?=$dA9dD7OF8qO5hncuiEIp;?>" class='input-line-main'></label>
              <label for="<?=$dA9dD7OF8qO5hncuiEIp;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$dg6iAiKFQPFYNsTacdtj;?>">
          <div class='input-div'>
            <label for="<?=$dg6iAiKFQPFYNsTacdtj;?>" class='input-icons icons-email'></label>
            <input type="mail" class='input-input' value="<?=$userData['email']?>" required id='<?=$dg6iAiKFQPFYNsTacdtj;?>'>
            <label for="<?=$dg6iAiKFQPFYNsTacdtj;?>" class='input-placeholder'>Почта</label>
            <label for="<?=$dg6iAiKFQPFYNsTacdtj;?>" class='input-line'>
              <label for="<?=$dg6iAiKFQPFYNsTacdtj;?>" class='input-line-main0'></label>
              <label for="<?=$dg6iAiKFQPFYNsTacdtj;?>" class='input-line-main'></label>
              <label for="<?=$dg6iAiKFQPFYNsTacdtj;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$dWqJfXMTRD5eusfOiUUK;?>">
          <div class='input-div'>
            <label for="<?=$dWqJfXMTRD5eusfOiUUK;?>" class='input-icons icons-tel'></label>
            <input type="tel" class='input-input' value="<?=$userData['phone']?>" required id='<?=$dWqJfXMTRD5eusfOiUUK;?>'>
            <label for="<?=$dWqJfXMTRD5eusfOiUUK;?>" class='input-placeholder'>Телефон</label>
            <label for="<?=$dWqJfXMTRD5eusfOiUUK;?>" class='input-line'>
              <label for="<?=$dWqJfXMTRD5eusfOiUUK;?>" class='input-line-main0'></label>
              <label for="<?=$dWqJfXMTRD5eusfOiUUK;?>" class='input-line-main'></label>
              <label for="<?=$dWqJfXMTRD5eusfOiUUK;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>
      <?php

        // $arrayCountry = '';
        // $countryArray = file("media/Country.ini");
        // foreach($countryArray as $line_num => $line) {
        //   $text = str_replace("\n", "", htmlspecialchars($line));
        //   $arrayCountry = $arrayCountry.'<option value="'.$text.'">'.$text.'</option>'."\n";
        // }
      ?>
      <div class='phone-class-margin'>
        <label class='input' for="<?=$dEhs7jdb2CKXxSEwjAbN;?>">
          <div class='input-div'>

            <label for="<?=$dEhs7jdb2CKXxSEwjAbN;?>" class='input-icons icons-point'></label>
            <select type="mail" class='input-input' required id='<?=$dEhs7jdb2CKXxSEwjAbN;?>'>
              <?php
                $founded = false;
                $countryArray = file("media/Country.ini");
                $user_country = str_replace("\r", '', str_replace("\n", '', htmlspecialchars($userData['country'], ENT_HTML5)));
                foreach($countryArray as $num => $line) {
                  $country = str_replace("\r", '', str_replace("\n", '', htmlspecialchars($line, ENT_HTML5)));
                  $output = '<option';
                  if(strcasecmp($user_country, $country) == 0) {
                    $output = $output.' selected="selected"';
                    $founded = true;
                  }
                  $output = $output.' value="'.$country.'">'.$country.'</option>'."\n";
                  echo($output);
                }
                if(!$founded && strlen($user_country) > 0) {
                  echo('<option selected="selected" value="'.$userData['country'].'" style="display: none;">'.$userData['country'].'</option>'."\n");
                }
              ?>

            </select>
            <label for="<?=$dEhs7jdb2CKXxSEwjAbN;?>" class='input-placeholder'>Страна</label>
            <label for="<?=$dEhs7jdb2CKXxSEwjAbN;?>" class='input-line'>
              <label for="<?=$dEhs7jdb2CKXxSEwjAbN;?>" class='input-line-main0'></label>
              <label for="<?=$dEhs7jdb2CKXxSEwjAbN;?>" class='input-line-main'></label>
              <label for="<?=$dEhs7jdb2CKXxSEwjAbN;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>

      <div class='phone-class-margin'>
        <label class='input' for="<?=$dFQp1gRHKYYewar6HGou;?>">
          <div class='input-div'>
            <label for="<?=$dFQp1gRHKYYewar6HGou;?>" class='input-icons icons-point'></label>
            <input type="tel" class='input-input' value="<?=$userData['city']?>" required id='<?=$dFQp1gRHKYYewar6HGou;?>'>
            <label for="<?=$dFQp1gRHKYYewar6HGou;?>" class='input-placeholder'>Город</label>
            <label for="<?=$dFQp1gRHKYYewar6HGou;?>" class='input-line'>
              <label for="<?=$dFQp1gRHKYYewar6HGou;?>" class='input-line-main0'></label>
              <label for="<?=$dFQp1gRHKYYewar6HGou;?>" class='input-line-main'></label>
              <label for="<?=$dFQp1gRHKYYewar6HGou;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
      </div>


      <div class='phone-class-margin'>
        <label class="checkbox" for="<?=$dVBpjqFzm7WP6PVrpJ0a;?>">
  				<input type="radio" name='gender-profile' <?php if($userData['gender'] == 'male'){echo('checked');} ?> class="checkbox-checked" id="<?=$dVBpjqFzm7WP6PVrpJ0a;?>" style="display: none;">
  				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="<?=$dVBpjqFzm7WP6PVrpJ0a;?>">
  					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="<?=$dVBpjqFzm7WP6PVrpJ0a;?>"></label>
  				</label>
  				<label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="<?=$dVBpjqFzm7WP6PVrpJ0a;?>">Мужской</label>
  			</label>
      </div>

      <div class='phone-class-margin'>
        <label class="checkbox" for="<?=$dhxaAHDLponKHoiCHO76;?>">
  				<input type="radio" name='gender-profile' <?php if($userData['gender'] == 'female'){echo('checked');} ?> class="checkbox-checked" id="<?=$dhxaAHDLponKHoiCHO76;?>" style="display: none;">
  				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="<?=$dhxaAHDLponKHoiCHO76;?>">
  					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="<?=$dhxaAHDLponKHoiCHO76;?>"></label>
  				</label>
  				<label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="<?=$dhxaAHDLponKHoiCHO76;?>">Женский</label>
  			</label>
      </div>


      <div class='login-btn-login' style='margin-bottom: 20px;'>
        <input type='button' onclick="windowOpen('#password-change')" class='window-btn' style='width: calc(80% - 30px); z-index: 0; position: relative; padding-top: 6px; padding-bottom: 6px;' value='Изменить пароль'>
      </div>

      <div class='login-btn-login'>
        <!-- Когда поле изменили, то кнопка сохранить активная (window-btn) и имеет свойство (cursor: pointer), если нет, о имеет класс (window-btn-noactive) и свойство (cursor: default) -->
        <input type='button' onclick="ProfileForm.main.save();" id="profile-form-save-btn-2" class='window-btn-noactive' style='cursor: default; z-index: 0; position: relative; width: 160px; padding-top: 6px; padding-bottom: 6px;' value='Сохранить'>
      </div>
    </div>
  </div>
  <div class='window' id='reCaptchaWindow' style='width: 400px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class='window-container'>
      <div class='window-container-title'>reCaptcha</div>
      <div class='window-container-text' style='margin-bottom: 20px;' id='captcha-v2-div'></div>
    </div>
  </div>
  <div class='window' id='login' style='width: 350px; max-width: 350px; overflow-x: hidden; opacity: 0; display: none; border-radius: 7px; transform: translate(-50%, -50%) scale(1.2);'>
    <div class='window-exit' title='Закрыть окно' onclick="windowClose(this)">
      <div class='window-exit-line1'></div>
      <div class='window-exit-line2'></div>
    </div>
    <div class="window-container-preloader2"></div>
    <div class='window-container'>
      <div class='window-container-title' style='text-align: center; max-width: 100%; margin-top: 30px;'>Авторизация</div>
      <div class='window-container-text' style='margin-top: 30px; margin-left: 20px; margin-right: 20px;'>

        <label class='input' for="<?=$deDaXzgeYUpqHVSER;?>">
          <div class='input-div'>
            <label for="<?=$deDaXzgeYUpqHVSER;?>" class='input-icons icons-user'></label>
            <input type="text" class='input-input' required id='<?=$deDaXzgeYUpqHVSER;?>'>
            <label for="<?=$deDaXzgeYUpqHVSER;?>" class='input-placeholder'>Логин</label>
            <label for="<?=$deDaXzgeYUpqHVSER;?>" class='input-line'>
              <label for="<?=$deDaXzgeYUpqHVSER;?>" class='input-line-main0'></label>
              <label for="<?=$deDaXzgeYUpqHVSER;?>" class='input-line-main'></label>
              <label for="<?=$deDaXzgeYUpqHVSER;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>

        <label class='input' for="<?=$d52bK8JIXP0zAF2MUVuZ;?>">
          <div class='input-div'>
            <label for="<?=$d52bK8JIXP0zAF2MUVuZ;?>" class='input-icons icons-lock'></label>
            <input type="password" class='input-input' required id='<?=$d52bK8JIXP0zAF2MUVuZ;?>'>
            <label for="<?=$d52bK8JIXP0zAF2MUVuZ;?>" class='input-placeholder'>Пароль</label>
            <label for="<?=$d52bK8JIXP0zAF2MUVuZ;?>" class='input-line'>
              <label for="<?=$d52bK8JIXP0zAF2MUVuZ;?>" class='input-line-main0'></label>
              <label for="<?=$d52bK8JIXP0zAF2MUVuZ;?>" class='input-line-main'></label>
              <label for="<?=$d52bK8JIXP0zAF2MUVuZ;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>
        <div class='login-password-recovery'>
          <a href='recovery.php' class='login-password-recovery-btn'>Забыли пароль?</a>
        </div>
        <div class='login-empty'></div>
      </div>
      <div class='login-btn-login'>
        <input type='button' onclick="login()" class='window-btn' style='z-index: 0; position: relative; width: 160px; padding-top: 6px; padding-bottom: 6px;' value='Войти'>
        <div class='login-btn-register'>
          <div>Новый пользователь?</div>
          <a href='register.php'>Регистрация</a>
        </div>
      </div>

    </div>
    <div class='login-preloader'>
      <div class='login-preloader-elem1'></div>
      <div class='login-preloader-elem2'></div>
      <div class='login-preloader-elem3'></div>
      <div class='login-preloader-elem11'></div>
      <div class='login-preloader-elem22'></div>
      <div class='login-preloader-elem33'></div>
      <div class='login-preloader-elem4'></div>
    </div>
  </div>
  <div class='window-shadow' onclick="windowClose(this,true)"></div>
</window>

<?php if(isset($userData['account']) && ($userData['account'] == 'poma098')):?>
<script>
  // windowOpen('#learning-online')
</script>
<?php endif; ?>
