<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template93c90b182c extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width , initial-scale=1">       
        <!-- CSS -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 7 */ ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 8 */ ?>/css/languages.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 9 */ ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" >
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 10 */ ?>/css/bootstrap-theme.css" rel="stylesheet" >
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 11 */ ?>/css/bootstrap-clockpicker.css" rel="stylesheet">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 12 */ ?>/css/mycss.css" rel="stylesheet" >
                
        <!-- JS -->
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 15 */ ?>/js/jquery.js"></script>
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 16 */ ?>/js/bootstrap.min.js"></script>
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 17 */ ?>/js/bootstrap-datetimepicker.js"></script>
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 18 */ ?>/js/bootstrap-datetimepicker.sk.js"></script>
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 19 */ ?>/js/bootstrap-clockpicker.js"></script>
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 20 */ ?>/js/netteForms.js"></script>
        <script  src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 21 */ ?>/js/jquery.nette.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>

        

        
        <title>SmartPas app</title>
</head>

<body>
<div class="container-fluid">
<?php
		$this->renderBlock('banner', $this->params, 'html');
?>
    
<?php
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>    <div<?php if ($_tmp = array_filter(['flash', $flash->type])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>><?php
			echo LR\Filters::escapeHtmlText($flash->message) /* line 34 */ ?></div>
<?php
			$iterations++;
		}
?>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:")) ?>">
<?php
		if ($user->loggedIn) {
			?>                        SmartPAS <?php echo LR\Filters::escapeHtmlText($nazov_planu) /* line 41 */ ?>

<?php
		}
		else {
?>
                        SmartPAS
<?php
		}
?>
                </a>
            </div>
<?php
		if ($user->loggedIn) {
?>
            <ul class="nav navbar-nav">
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("Homepage:")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.home")) ?></a></li>
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("Citacka:default")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Citacka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.citacky")) ?></a></li>
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("RFID:default")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.rfid")) ?></a></li>
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("Osoby:default")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Osoby:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.osoby")) ?></a></li>
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("Dochadzka:default")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.dochadzka")) ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("Sign:out")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:out")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.sign_out")) ?></a></li> 
                <li <?php
			if ($this->global->uiPresenter->isLinkCurrent("User:default")) {
				?>class="active"<?php
			}
			?>><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("User:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText($meno_uzivatela) /* line 57 */ ?></a></li> 
                <li style="margin-top: 8px;">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="lang-xs lang-lbl" lang="<?php echo LR\Filters::escapeHtmlAttr($activeLocale) /* line 61 */ ?>"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("this", ['locale' => 'en'])) ?>"><span class="lang-xs lang-lbl" lang="en"></span></a></li>
                            <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("this", ['locale' => 'sk'])) ?>"><span class="lang-xs lang-lbl" lang="sk"></span></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
<?php
		}
		else {
?>
            <ul class="nav navbar-nav">
                <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:in")) ?>"><span class="glyphicon glyphicon-log-in"> </span> <?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.sign_in")) ?></a></li>
                <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:register")) ?>"><span class="glyphicon glyphicon-user"> </span> <?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.register")) ?></a></li>
                
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li style="margin-top: 8px;">
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span class="lang-xs lang-lbl" lang="<?php echo LR\Filters::escapeHtmlAttr($activeLocale) /* line 80 */ ?>"></span>
                        </button>
                        <ul class="dropdown-menu" >
                            <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("this", ['locale' => 'en'])) ?>"><span class="lang-xs lang-lbl" lang="en"></span></a></li>
                            <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("this", ['locale' => 'sk'])) ?>"><span class="lang-xs lang-lbl" lang="sk"></span></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
<?php
		}
?>
        </div>
    </nav>
    
<?php
		$this->renderBlock('title', $this->params, 'html');
?>
    
<?php
		$this->renderBlock('content', $this->params, 'html');
?>
    
   
</body>
</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 34');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
