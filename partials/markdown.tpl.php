<div class="modal-body">
<link rel="stylesheet" type="text/css" href="bower_components/highlight/styles/solarized_dark.css">
  <a class="close" ng-click="closePartial()"></a>
  <div style="width:90%; margin: 0 auto; border: 1px #333 solid;">
    <div style="width:90%; margin: 2em auto;">
     <?echo passthru ('markdown ' . '../'.$_GET["src"]);?>
   </div>
  </div>
</div>
