<?php
  $rand_hash_kek = 'a'.sha1(time().'1fk!x8.slm2/'.rand(1000, 9999).'a#xb'.rand(1000, 9999).'a$nxj,c7a8');
  $rand_euserid_var = $rand_hash_kek.'a';
  $rand_euserid_val = $rand_hash_kek.'b';
  $rand_ec = $rand_hash_kek.'c';
  $rand_ec_check = $rand_hash_kek.'d';
  $rand_srq = $rand_hash_kek.'e';
  $rand_fp_func = $rand_hash_kek.'f';
  $rand_hash_kek2 = sha1(rand(1000, 9999).'a&c9z8Z.uq'.time().rand(1000, 9999).'az6av.skA6z');
  $rand_hash_kek3 = 'key_'.sha1(time().rand(10000, 99999));
  $_SESSION[$rand_hash_kek3] = $rand_hash_kek2;
  $recid = false;
  if(is_object($article_data) && property_exists($article_data, 'record_id')) $recid = $article_data->record_id;
?>
<script>
  var <?=$rand_ec?> = new evercookie();
  var <?=$rand_euserid_var?> = null;
  var <?=$rand_fp_func?> = function() {
    Fingerprint2.get(function(components) {
      <?=$rand_euserid_var?> = Fingerprint2.x64hash128(components.map(function (pair) { return pair.value }).join(), 31);
      <?=$rand_ec_check?>();
    });
  }
  function <?=$rand_srq?>(hash) {
    $.ajax({
      type: 'POST',
      url: 'php/view_key.php',
      data: {
        view: '<?=$rand_hash_kek3?>',
        view_key: '<?=$rand_hash_kek2?>',
        record: <?=$recid?>,
        id: hash
      },
      success: function(response) { }
    });
  }
  function <?=$rand_ec_check?>() {
    <?=$rand_ec?>.get('_e_userid', function(best, all) {
      if((typeof(best) == 'null') || (best == 'null')) {
        var hash = <?=$rand_euserid_var?>;
        if((typeof(hash) != 'string') || (hash == 'null') || (hash == '')) {
          hash = '<?=$rand_euserid_val?>';
        }
        <?=$rand_ec?>.set("_e_userid", hash);
      }
      else {
        <?=$rand_euserid_var?> = best;
      }
      <?=$rand_srq?>(<?=$rand_euserid_var?>);
    });
  }
  if(window.requestIdleCallback) {
    cancelId = requestIdleCallback(<?=$rand_fp_func?>);
    cancelFunction = cancelIdleCallback;
  }
  else {
    cancelId = setTimeout(<?=$rand_fp_func?>, 500);
    cancelFunction = clearTimeout;
  }
</script>
