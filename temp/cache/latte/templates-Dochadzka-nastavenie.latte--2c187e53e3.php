<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Dochadzka/nastavenie.latte

use Latte\Runtime as LR;

class Template2c187e53e3 extends Latte\Runtime\Template
{
	public $blocks = [
		'banner' => 'blockBanner',
		'title' => 'blockTitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'banner' => 'html',
		'title' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('banner', get_defined_vars());
?>

<?php
		$this->renderBlock('title', get_defined_vars());
?>


<?php
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.dochadzka")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>

   

<?php
		if ($user->loggedIn) {
			?><h2><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.nastavenie_prehladov")) ?></h2>    
<div>
    <ul class="nav nav-pills">
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.current")) ?></a></li>
        <li ><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:prehlad")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.prehlad")) ?></a></li>
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:nastavenie")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.settings")) ?></a></li>
    </ul>
</div>
    
<?php
			/* line 24 */ $_tmp = $this->global->uiControl->getComponent("nastavenieForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>

<hr>
<h2><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.nastavenie_prac_doby")) ?></h2>

<div>
        <?php
			/* line 30 */
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["nastaveniePracovnejDobyForm"], []);
?>

    
        <div class="row required">
            <div class="col-sm-1 required"> <?php if ($_label = end($this->global->formsStack)["prac_doba"]->getLabel()) echo $_label ?> </div>
            <div class="col-sm-2 input-group"> <?php echo end($this->global->formsStack)["prac_doba"]->getControl() /* line 34 */ ?> </div>
        </div>
    
        <div class="row required">
            <div class="col-xs-1 required"> <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.smena1")) ?> </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["prichod1"]->getLabel()) echo $_label ?> </div>
            <div class="col-xs-2 input-group" id="form-prichod1" data-align="top" data-autoclose="true">
                 <?php echo end($this->global->formsStack)["prichod1"]->getControl() /* line 41 */ ?>

                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["odchod1"]->getLabel()) echo $_label ?> </div>
            <div class="col-xs-2 input-group" id="form-odchod1" data-align="top" data-autoclose="true">
                 <?php echo end($this->global->formsStack)["odchod1"]->getControl() /* line 48 */ ?>

                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
                
        <div class="row required" id="2_smeny">
            <div class="col-xs-1 required"> <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.smena2")) ?> </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["prichod2"]->getLabel()) echo $_label ?> </div>
            <div class="col-xs-2 input-group" id="form-prichod2" data-align="top" data-autoclose="true">
                 <?php echo end($this->global->formsStack)["prichod2"]->getControl() /* line 59 */ ?>

                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["odchod2"]->getLabel()) echo $_label ?> </div>
            <div class="col-xs-2 input-group" id="form-odchod2" data-align="top" data-autoclose="true">
                 <?php echo end($this->global->formsStack)["odchod2"]->getControl() /* line 66 */ ?>

                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
                
        <div class="row required" id="3_smeny">
            <div class="col-xs-1 required"> <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.smena3")) ?> </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["prichod3"]->getLabel()) echo $_label ?> </div>
            <div class="col-xs-2 input-group" id="form-prichod3" data-align="top" data-autoclose="true">
                 <?php echo end($this->global->formsStack)["prichod3"]->getControl() /* line 77 */ ?>

                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["odchod3"]->getLabel()) echo $_label ?> </div>
            <div class="col-xs-2 input-group" id="form-odchod3" data-align="top" data-autoclose="true">
                 <?php echo end($this->global->formsStack)["odchod3"]->getControl() /* line 84 */ ?>

                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div> 
                
        <div>
        <?php echo end($this->global->formsStack)["send"]->getControl() /* line 92 */ ?>

        </div> 
    
        <?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

</div>

    



 <script type="text/javascript">
    $('#form-prichod1').clockpicker();
    $('#form-odchod1').clockpicker();
    $('#form-prichod2').clockpicker();
    $('#form-odchod2').clockpicker();
    $('#form-prichod3').clockpicker();
    $('#form-odchod3').clockpicker();
</script>   

<?php
		}
?> 
<?php
	}

}
