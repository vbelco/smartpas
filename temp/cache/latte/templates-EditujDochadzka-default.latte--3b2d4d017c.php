<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/EditujDochadzka/default.latte

use Latte\Runtime as LR;

class Template3b2d4d017c extends Latte\Runtime\Template
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
		if (isset($this->params['index'])) trigger_error('Variable $index overwritten in foreach on line 65');
		if (isset($this->params['den'])) trigger_error('Variable $den overwritten in foreach on line 65');
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
?>    
<div>
    <ul class="nav nav-pills">
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.current")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:prehlad")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.prehlad")) ?></a></li>
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("EditujDochadzka:")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.edituj")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:nastavenie")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.settings")) ?></a></li>
    </ul>
</div>
    
<div class="container">
    <div class="panel panel-default"> 
    <div class="panel-body">
    <?php
			/* line 25 */
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["prehladForm"], []);
?>

        <div class="row required">
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["datum_od"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4 input-group date required" id="form-date-od" >
                        <?php echo end($this->global->formsStack)["datum_od"]->getControl() /* line 29 */ ?>

                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["datum_do"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4 input-group date required" id ="form-date-do">
                        <?php echo end($this->global->formsStack)["datum_do"]->getControl() /* line 35 */ ?>

                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-1"> <?php if ($_label = end($this->global->formsStack)["osoba"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4"> <?php echo end($this->global->formsStack)["osoba"]->getControl() /* line 42 */ ?></div>
        </div>
        <div class="my_last_row">
            <div class="col-sm-1"> </div>
            <div class="col-sm-4"><?php echo end($this->global->formsStack)["send"]->getControl() /* line 46 */ ?></div>
        </div>
    <?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

    </div>
    </div>
</div>   

<?php
			if (isset ($osoba)) {
				?>    <h3> <?php echo LR\Filters::escapeHtmlText($osoba->meno) /* line 54 */ ?> </h3>
<?php
			}
?>

<?php
			if (isset ($dochadzkaOsoby)) {
?>
    <div class="table-responsive">
    <table class="table table-hover"">
        <tr>
                <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.date")) ?></th>
                <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.prichod")) ?></th>
                <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.odchod")) ?></th>
            </tr>
<?php
				$iterations = 0;
				foreach ($dochadzkaOsoby->dochadzka_den as $index => $den) {
?>
                <tr>
                    <td>
                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $den, "d. m. Y")) /* line 68 */ ?>

                    </td>
                    <td>
<?php
					if ($dochadzkaOsoby->dochadzka_prichod) {
						?>                            <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $dochadzkaOsoby->dochadzka_prichod[$index], "G:i:s")) /* line 72 */ ?>

<?php
					}
					else {
?>
                            
                            <?php
						/* line 75 */
						echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["editPrichodForm-$index"], []);
?>

                                <span id="prichod-<?php echo LR\Filters::escapeHtmlAttr($index) /* line 76 */ ?>" data-align="top" data-autoclose="true">
                                    <?php echo end($this->global->formsStack)["prichod"]->getControl() /* line 77 */ ?>

                                </span>
                                <?php echo end($this->global->formsStack)["send"]->getControl() /* line 79 */ ?>

                            <?php
						echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

                            <script type="text/javascript">
                                $("#prichod-<?php echo LR\Filters::escapeJs($index) /* line 82 */ ?>").clockpicker();
                            </script> 
<?php
					}
?>
                    </td>
                    <td>
<?php
					if ($dochadzkaOsoby->dochadzka_odchod[$index]) {
						?>                            <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $dochadzkaOsoby->dochadzka_odchod[$index], "G:i:s")) /* line 88 */ ?>

<?php
					}
					else {
						?>                            <?php
						/* line 90 */
						echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["editOdchodForm-$index"], []);
?>

                                <span id="odchod-<?php echo LR\Filters::escapeHtmlAttr($index) /* line 91 */ ?>" data-align="top" data-autoclose="true">
                                    <?php echo end($this->global->formsStack)["odchod"]->getControl() /* line 92 */ ?>

                                </span>
                                <?php echo end($this->global->formsStack)["send"]->getControl() /* line 94 */ ?>

                            <?php
						echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

                            </div>
                            <script type="text/javascript">
                                $("#odchod-<?php echo LR\Filters::escapeJs($index) /* line 98 */ ?>").clockpicker();
                            </script> 
<?php
					}
?>
                    </td>
                </tr>
<?php
					$iterations++;
				}
?>
    </table>
    </div>
<?php
			}
?>

<script type="text/javascript">
    $('#form-date-od').datetimepicker({
        minView: 2,
        autoclose: 1,
        todayBtn:  1,
        useCurrent: true,
        format: "dd.mm.yyyy",
        language:  <?php echo LR\Filters::escapeJs($activeLocale) /* line 115 */ ?>

    });
    $('#form-date-do').datetimepicker({
        minView: 2,
        autoclose: 1,
        todayBtn:  1,
        format: "dd.mm.yyyy",
        language:  <?php echo LR\Filters::escapeJs($activeLocale) /* line 122 */ ?>

    });
</script> 

<?php
		}
?> 
<?php
	}

}
