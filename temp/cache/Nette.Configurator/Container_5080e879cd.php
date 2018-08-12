<?php
// source: C:\xampp\htdocs\sandbox\app/config/config.local.neon 

class Container_5080e879cd extends Nette\DI\Container
{
	protected $meta = [
		'types' => [
			'Nette\Application\Application' => [1 => ['application.application']],
			'Nette\Application\IPresenterFactory' => [1 => ['application.presenterFactory']],
			'Nette\Application\LinkGenerator' => [1 => ['application.linkGenerator']],
			'Nette\Caching\Storages\IJournal' => [1 => ['cache.journal']],
			'Nette\Caching\IStorage' => [1 => ['cache.storage']],
			'Nette\Database\Connection' => [1 => ['database.default.connection']],
			'Nette\Database\IStructure' => [1 => ['database.default.structure']],
			'Nette\Database\Structure' => [1 => ['database.default.structure']],
			'Nette\Database\IConventions' => [1 => ['database.default.conventions']],
			'Nette\Database\Conventions\DiscoveredConventions' => [1 => ['database.default.conventions']],
			'Nette\Database\Context' => [1 => ['database.default.context']],
			'Nette\Http\RequestFactory' => [1 => ['http.requestFactory']],
			'Nette\Http\IRequest' => [1 => ['http.request']],
			'Nette\Http\Request' => [1 => ['http.request']],
			'Nette\Http\IResponse' => [1 => ['http.response']],
			'Nette\Http\Response' => [1 => ['http.response']],
			'Nette\Http\Context' => [1 => ['http.context']],
			'Nette\Bridges\ApplicationLatte\ILatteFactory' => [1 => ['latte.latteFactory']],
			'Nette\Application\UI\ITemplateFactory' => [1 => ['latte.templateFactory']],
			'Nette\Mail\IMailer' => [1 => ['mail.mailer']],
			'Nette\Application\IRouter' => [1 => ['routing.router']],
			'Nette\Security\IUserStorage' => [1 => ['security.userStorage']],
			'Nette\Security\User' => [1 => ['security.user']],
			'Nette\Http\Session' => [1 => ['session.session']],
			'Tracy\ILogger' => [1 => ['tracy.logger']],
			'Tracy\BlueScreen' => [1 => ['tracy.blueScreen']],
			'Tracy\Bar' => [1 => ['tracy.bar']],
			'Symfony\Component\Translation\Translator' => [1 => ['translation.default']],
			'Symfony\Component\Translation\TranslatorBagInterface' => [1 => ['translation.default']],
			'Symfony\Component\Translation\TranslatorInterface' => [1 => ['translation.default']],
			'Kdyby\Translation\ITranslator' => [1 => ['translation.default']],
			'Nette\Localization\ITranslator' => [1 => ['translation.default']],
			'Kdyby\Translation\Translator' => [1 => ['translation.default']],
			'Nette\Object' => [
				1 => [
					'translation.catalogueCompiler',
					'translation.panel',
					'translation.userLocaleResolver.acceptHeader',
					'translation.userLocaleResolver.session',
					'translation.helpers',
					'translation.fallbackResolver',
					'translation.catalogueFactory',
					'translation.loader',
					'61_App_Model_Citacka',
					'62_App_Model_Dochadzka',
					'63_App_Model_Organizacia',
					'64_App_Model_Osoba',
					'65_App_Model_RFID',
					'66_App_Model_Uzivatel',
					'authenticator',
					'application.4',
				],
				0 => ['translation.userLocaleResolver.param', 'translation.extractor.latte'],
			],
			'Kdyby\Translation\CatalogueCompiler' => [1 => ['translation.catalogueCompiler']],
			'Tracy\IBarPanel' => [1 => ['translation.panel']],
			'Kdyby\Translation\Diagnostics\Panel' => [1 => ['translation.panel']],
			'Kdyby\Translation\IUserLocaleResolver' => [
				['translation.userLocaleResolver.param'],
				[
					'translation.userLocaleResolver.acceptHeader',
					'translation.userLocaleResolver.session',
					'translation.userLocaleResolver',
				],
			],
			'Kdyby\Translation\LocaleResolver\LocaleParamResolver' => [['translation.userLocaleResolver.param']],
			'Kdyby\Translation\LocaleResolver\AcceptHeaderResolver' => [1 => ['translation.userLocaleResolver.acceptHeader']],
			'Kdyby\Translation\LocaleResolver\SessionResolver' => [1 => ['translation.userLocaleResolver.session']],
			'Kdyby\Translation\TemplateHelpers' => [1 => ['translation.helpers']],
			'Kdyby\Translation\FallbackResolver' => [1 => ['translation.fallbackResolver']],
			'Kdyby\Translation\CatalogueFactory' => [1 => ['translation.catalogueFactory']],
			'Symfony\Component\Translation\MessageSelector' => [1 => ['translation.selector']],
			'Symfony\Component\Translation\Extractor\ExtractorInterface' => [1 => ['translation.extractor'], 0 => ['translation.extractor.latte']],
			'Symfony\Component\Translation\Extractor\ChainExtractor' => [1 => ['translation.extractor']],
			'Kdyby\Translation\Extractors\LatteExtractor' => [['translation.extractor.latte']],
			'Symfony\Component\Translation\Writer\TranslationWriter' => [1 => ['translation.writer']],
			'Symfony\Component\Translation\Dumper\FileDumper' => [
				[
					'translation.dumper.php',
					'translation.dumper.xliff',
					'translation.dumper.po',
					'translation.dumper.mo',
					'translation.dumper.yml',
					'translation.dumper.neon',
					'translation.dumper.qt',
					'translation.dumper.csv',
					'translation.dumper.ini',
					'translation.dumper.res',
				],
			],
			'Symfony\Component\Translation\Dumper\DumperInterface' => [
				[
					'translation.dumper.php',
					'translation.dumper.xliff',
					'translation.dumper.po',
					'translation.dumper.mo',
					'translation.dumper.yml',
					'translation.dumper.neon',
					'translation.dumper.qt',
					'translation.dumper.csv',
					'translation.dumper.ini',
					'translation.dumper.res',
				],
			],
			'Symfony\Component\Translation\Dumper\PhpFileDumper' => [['translation.dumper.php']],
			'Symfony\Component\Translation\Dumper\XliffFileDumper' => [['translation.dumper.xliff']],
			'Symfony\Component\Translation\Dumper\PoFileDumper' => [['translation.dumper.po']],
			'Symfony\Component\Translation\Dumper\MoFileDumper' => [['translation.dumper.mo']],
			'Symfony\Component\Translation\Dumper\YamlFileDumper' => [['translation.dumper.yml']],
			'Kdyby\Translation\Dumper\NeonFileDumper' => [['translation.dumper.neon']],
			'Symfony\Component\Translation\Dumper\QtFileDumper' => [['translation.dumper.qt']],
			'Symfony\Component\Translation\Dumper\CsvFileDumper' => [['translation.dumper.csv']],
			'Symfony\Component\Translation\Dumper\IniFileDumper' => [['translation.dumper.ini']],
			'Symfony\Component\Translation\Dumper\IcuResFileDumper' => [['translation.dumper.res']],
			'Kdyby\Translation\IResourceLoader' => [1 => ['translation.loader']],
			'Kdyby\Translation\TranslationLoader' => [1 => ['translation.loader']],
			'Symfony\Component\Translation\Loader\FileLoader' => [
				[
					'translation.loader.php',
					'translation.loader.yml',
					'translation.loader.po',
					'translation.loader.mo',
					'translation.loader.csv',
					'translation.loader.ini',
					'translation.loader.json',
				],
			],
			'Symfony\Component\Translation\Loader\ArrayLoader' => [
				[
					'translation.loader.php',
					'translation.loader.yml',
					'translation.loader.po',
					'translation.loader.mo',
					'translation.loader.csv',
					'translation.loader.ini',
					'translation.loader.json',
					'translation.loader.neon',
				],
			],
			'Symfony\Component\Translation\Loader\LoaderInterface' => [
				[
					'translation.loader.php',
					'translation.loader.yml',
					'translation.loader.xlf',
					'translation.loader.po',
					'translation.loader.mo',
					'translation.loader.ts',
					'translation.loader.csv',
					'translation.loader.res',
					'translation.loader.dat',
					'translation.loader.ini',
					'translation.loader.json',
					'translation.loader.neon',
				],
			],
			'Symfony\Component\Translation\Loader\PhpFileLoader' => [['translation.loader.php']],
			'Symfony\Component\Translation\Loader\YamlFileLoader' => [['translation.loader.yml']],
			'Symfony\Component\Translation\Loader\XliffFileLoader' => [['translation.loader.xlf']],
			'Symfony\Component\Translation\Loader\PoFileLoader' => [['translation.loader.po']],
			'Symfony\Component\Translation\Loader\MoFileLoader' => [['translation.loader.mo']],
			'Symfony\Component\Translation\Loader\QtFileLoader' => [['translation.loader.ts']],
			'Symfony\Component\Translation\Loader\CsvFileLoader' => [['translation.loader.csv']],
			'Symfony\Component\Translation\Loader\IcuResFileLoader' => [['translation.loader.res', 'translation.loader.dat']],
			'Symfony\Component\Translation\Loader\IcuDatFileLoader' => [['translation.loader.dat']],
			'Symfony\Component\Translation\Loader\IniFileLoader' => [['translation.loader.ini']],
			'Symfony\Component\Translation\Loader\JsonFileLoader' => [['translation.loader.json']],
			'Kdyby\Translation\Loader\NeonFileLoader' => [['translation.loader.neon']],
			'App\Model\Citacka' => [1 => ['61_App_Model_Citacka']],
			'App\Model\Dochadzka' => [1 => ['62_App_Model_Dochadzka']],
			'App\Model\Organizacia' => [1 => ['63_App_Model_Organizacia']],
			'App\Model\Osoba' => [1 => ['64_App_Model_Osoba']],
			'App\Model\RFID' => [1 => ['65_App_Model_RFID']],
			'App\Model\Uzivatel' => [1 => ['66_App_Model_Uzivatel']],
			'Nette\Security\IAuthenticator' => [1 => ['authenticator']],
			'MyAuthenticator' => [1 => ['authenticator']],
			'App\Presenters\BasePresenter' => [
				1 => [
					'application.1',
					'application.2',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\UI\Presenter' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\UI\Control' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\UI\Component' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\ComponentModel\Container' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\ComponentModel\Component' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\UI\IRenderable' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\ComponentModel\IContainer' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\ComponentModel\IComponent' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\UI\ISignalReceiver' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\UI\IStatePersistent' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'ArrayAccess' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
				],
			],
			'Nette\Application\IPresenter' => [
				[
					'application.1',
					'application.2',
					'application.3',
					'application.4',
					'application.5',
					'application.6',
					'application.7',
					'application.8',
					'application.9',
					'application.10',
					'application.11',
				],
			],
			'App\Presenters\CitackaPresenter' => [1 => ['application.1']],
			'App\Presenters\DochadzkaPresenter' => [1 => ['application.2']],
			'App\Presenters\Error4xxPresenter' => [1 => ['application.3']],
			'App\Presenters\ErrorPresenter' => [1 => ['application.4']],
			'App\Presenters\HomepagePresenter' => [1 => ['application.5']],
			'App\Presenters\OsobyPresenter' => [1 => ['application.6']],
			'App\Presenters\RFIDPresenter' => [1 => ['application.7']],
			'App\Presenters\SignPresenter' => [1 => ['application.8']],
			'App\Presenters\UserPresenter' => [1 => ['application.9']],
			'NetteModule\ErrorPresenter' => [1 => ['application.10']],
			'NetteModule\MicroPresenter' => [1 => ['application.11']],
			'Nette\DI\Container' => [1 => ['container']],
		],
		'services' => [
			'61_App_Model_Citacka' => 'App\Model\Citacka',
			'62_App_Model_Dochadzka' => 'App\Model\Dochadzka',
			'63_App_Model_Organizacia' => 'App\Model\Organizacia',
			'64_App_Model_Osoba' => 'App\Model\Osoba',
			'65_App_Model_RFID' => 'App\Model\RFID',
			'66_App_Model_Uzivatel' => 'App\Model\Uzivatel',
			'application.1' => 'App\Presenters\CitackaPresenter',
			'application.10' => 'NetteModule\ErrorPresenter',
			'application.11' => 'NetteModule\MicroPresenter',
			'application.2' => 'App\Presenters\DochadzkaPresenter',
			'application.3' => 'App\Presenters\Error4xxPresenter',
			'application.4' => 'App\Presenters\ErrorPresenter',
			'application.5' => 'App\Presenters\HomepagePresenter',
			'application.6' => 'App\Presenters\OsobyPresenter',
			'application.7' => 'App\Presenters\RFIDPresenter',
			'application.8' => 'App\Presenters\SignPresenter',
			'application.9' => 'App\Presenters\UserPresenter',
			'application.application' => 'Nette\Application\Application',
			'application.linkGenerator' => 'Nette\Application\LinkGenerator',
			'application.presenterFactory' => 'Nette\Application\IPresenterFactory',
			'authenticator' => 'MyAuthenticator',
			'cache.journal' => 'Nette\Caching\Storages\IJournal',
			'cache.storage' => 'Nette\Caching\IStorage',
			'container' => 'Nette\DI\Container',
			'database.default.connection' => 'Nette\Database\Connection',
			'database.default.context' => 'Nette\Database\Context',
			'database.default.conventions' => 'Nette\Database\Conventions\DiscoveredConventions',
			'database.default.structure' => 'Nette\Database\Structure',
			'http.context' => 'Nette\Http\Context',
			'http.request' => 'Nette\Http\Request',
			'http.requestFactory' => 'Nette\Http\RequestFactory',
			'http.response' => 'Nette\Http\Response',
			'latte.latteFactory' => 'Latte\Engine',
			'latte.templateFactory' => 'Nette\Application\UI\ITemplateFactory',
			'mail.mailer' => 'Nette\Mail\IMailer',
			'routing.router' => 'Nette\Application\IRouter',
			'security.user' => 'Nette\Security\User',
			'security.userStorage' => 'Nette\Security\IUserStorage',
			'session.session' => 'Nette\Http\Session',
			'tracy.bar' => 'Tracy\Bar',
			'tracy.blueScreen' => 'Tracy\BlueScreen',
			'tracy.logger' => 'Tracy\ILogger',
			'translation.catalogueCompiler' => 'Kdyby\Translation\CatalogueCompiler',
			'translation.catalogueFactory' => 'Kdyby\Translation\CatalogueFactory',
			'translation.default' => 'Kdyby\Translation\Translator',
			'translation.dumper.csv' => 'Symfony\Component\Translation\Dumper\CsvFileDumper',
			'translation.dumper.ini' => 'Symfony\Component\Translation\Dumper\IniFileDumper',
			'translation.dumper.mo' => 'Symfony\Component\Translation\Dumper\MoFileDumper',
			'translation.dumper.neon' => 'Kdyby\Translation\Dumper\NeonFileDumper',
			'translation.dumper.php' => 'Symfony\Component\Translation\Dumper\PhpFileDumper',
			'translation.dumper.po' => 'Symfony\Component\Translation\Dumper\PoFileDumper',
			'translation.dumper.qt' => 'Symfony\Component\Translation\Dumper\QtFileDumper',
			'translation.dumper.res' => 'Symfony\Component\Translation\Dumper\IcuResFileDumper',
			'translation.dumper.xliff' => 'Symfony\Component\Translation\Dumper\XliffFileDumper',
			'translation.dumper.yml' => 'Symfony\Component\Translation\Dumper\YamlFileDumper',
			'translation.extractor' => 'Symfony\Component\Translation\Extractor\ChainExtractor',
			'translation.extractor.latte' => 'Kdyby\Translation\Extractors\LatteExtractor',
			'translation.fallbackResolver' => 'Kdyby\Translation\FallbackResolver',
			'translation.helpers' => 'Kdyby\Translation\TemplateHelpers',
			'translation.loader' => 'Kdyby\Translation\TranslationLoader',
			'translation.loader.csv' => 'Symfony\Component\Translation\Loader\CsvFileLoader',
			'translation.loader.dat' => 'Symfony\Component\Translation\Loader\IcuDatFileLoader',
			'translation.loader.ini' => 'Symfony\Component\Translation\Loader\IniFileLoader',
			'translation.loader.json' => 'Symfony\Component\Translation\Loader\JsonFileLoader',
			'translation.loader.mo' => 'Symfony\Component\Translation\Loader\MoFileLoader',
			'translation.loader.neon' => 'Kdyby\Translation\Loader\NeonFileLoader',
			'translation.loader.php' => 'Symfony\Component\Translation\Loader\PhpFileLoader',
			'translation.loader.po' => 'Symfony\Component\Translation\Loader\PoFileLoader',
			'translation.loader.res' => 'Symfony\Component\Translation\Loader\IcuResFileLoader',
			'translation.loader.ts' => 'Symfony\Component\Translation\Loader\QtFileLoader',
			'translation.loader.xlf' => 'Symfony\Component\Translation\Loader\XliffFileLoader',
			'translation.loader.yml' => 'Symfony\Component\Translation\Loader\YamlFileLoader',
			'translation.panel' => 'Kdyby\Translation\Diagnostics\Panel',
			'translation.selector' => 'Symfony\Component\Translation\MessageSelector',
			'translation.userLocaleResolver' => 'Kdyby\Translation\IUserLocaleResolver',
			'translation.userLocaleResolver.acceptHeader' => 'Kdyby\Translation\LocaleResolver\AcceptHeaderResolver',
			'translation.userLocaleResolver.param' => 'Kdyby\Translation\LocaleResolver\LocaleParamResolver',
			'translation.userLocaleResolver.session' => 'Kdyby\Translation\LocaleResolver\SessionResolver',
			'translation.writer' => 'Symfony\Component\Translation\Writer\TranslationWriter',
		],
		'tags' => [
			'inject' => [
				'application.1' => true,
				'application.10' => true,
				'application.11' => true,
				'application.2' => true,
				'application.3' => true,
				'application.4' => true,
				'application.5' => true,
				'application.6' => true,
				'application.7' => true,
				'application.8' => true,
				'application.9' => true,
			],
			'nette.presenter' => [
				'application.1' => 'App\Presenters\CitackaPresenter',
				'application.10' => 'NetteModule\ErrorPresenter',
				'application.11' => 'NetteModule\MicroPresenter',
				'application.2' => 'App\Presenters\DochadzkaPresenter',
				'application.3' => 'App\Presenters\Error4xxPresenter',
				'application.4' => 'App\Presenters\ErrorPresenter',
				'application.5' => 'App\Presenters\HomepagePresenter',
				'application.6' => 'App\Presenters\OsobyPresenter',
				'application.7' => 'App\Presenters\RFIDPresenter',
				'application.8' => 'App\Presenters\SignPresenter',
				'application.9' => 'App\Presenters\UserPresenter',
			],
			'translation.dumper' => [
				'translation.dumper.csv' => 'csv',
				'translation.dumper.ini' => 'ini',
				'translation.dumper.mo' => 'mo',
				'translation.dumper.neon' => 'neon',
				'translation.dumper.php' => 'php',
				'translation.dumper.po' => 'po',
				'translation.dumper.qt' => 'qt',
				'translation.dumper.res' => 'res',
				'translation.dumper.xliff' => 'xliff',
				'translation.dumper.yml' => 'yml',
			],
			'translation.extractor' => ['translation.extractor.latte' => 'latte'],
			'translation.loader' => [
				'translation.loader.csv' => 'csv',
				'translation.loader.dat' => 'dat',
				'translation.loader.ini' => 'ini',
				'translation.loader.json' => 'json',
				'translation.loader.mo' => 'mo',
				'translation.loader.neon' => 'neon',
				'translation.loader.php' => 'php',
				'translation.loader.po' => 'po',
				'translation.loader.res' => 'res',
				'translation.loader.ts' => 'ts',
				'translation.loader.xlf' => 'xlf',
				'translation.loader.yml' => 'yml',
			],
		],
		'aliases' => [
			'application' => 'application.application',
			'cacheStorage' => 'cache.storage',
			'database.default' => 'database.default.connection',
			'httpRequest' => 'http.request',
			'httpResponse' => 'http.response',
			'nette.cacheJournal' => 'cache.journal',
			'nette.database.default' => 'database.default',
			'nette.database.default.context' => 'database.default.context',
			'nette.httpContext' => 'http.context',
			'nette.httpRequestFactory' => 'http.requestFactory',
			'nette.latteFactory' => 'latte.latteFactory',
			'nette.mailer' => 'mail.mailer',
			'nette.presenterFactory' => 'application.presenterFactory',
			'nette.templateFactory' => 'latte.templateFactory',
			'nette.userStorage' => 'security.userStorage',
			'router' => 'routing.router',
			'session' => 'session.session',
			'user' => 'security.user',
		],
	];


	public function __construct(array $params = [])
	{
		$this->parameters = $params;
		$this->parameters += [
			'appDir' => 'C:\xampp\htdocs\sandbox\app',
			'wwwDir' => 'C:\xampp\htdocs\sandbox\www',
			'debugMode' => true,
			'productionMode' => false,
			'consoleMode' => false,
			'tempDir' => 'C:\xampp\htdocs\sandbox\app/../temp',
		];
	}


	public function createService__61_App_Model_Citacka(): App\Model\Citacka
	{
		$service = new App\Model\Citacka($this->getService('database.default.context'), $this->getService('translation.default'));
		return $service;
	}


	public function createService__62_App_Model_Dochadzka(): App\Model\Dochadzka
	{
		$service = new App\Model\Dochadzka($this->getService('database.default.context'));
		return $service;
	}


	public function createService__63_App_Model_Organizacia(): App\Model\Organizacia
	{
		$service = new App\Model\Organizacia($this->getService('database.default.context'),
			$this->getService('translation.default'));
		return $service;
	}


	public function createService__64_App_Model_Osoba(): App\Model\Osoba
	{
		$service = new App\Model\Osoba($this->getService('database.default.context'));
		return $service;
	}


	public function createService__65_App_Model_RFID(): App\Model\RFID
	{
		$service = new App\Model\RFID($this->getService('database.default.context'), $this->getService('64_App_Model_Osoba'));
		return $service;
	}


	public function createService__66_App_Model_Uzivatel(): App\Model\Uzivatel
	{
		$service = new App\Model\Uzivatel($this->getService('database.default.context'), $this->getService('translation.default'));
		return $service;
	}


	public function createServiceApplication__1(): App\Presenters\CitackaPresenter
	{
		$service = new App\Presenters\CitackaPresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'), $this->getService('61_App_Model_Citacka'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__10(): NetteModule\ErrorPresenter
	{
		$service = new NetteModule\ErrorPresenter($this->getService('tracy.logger'));
		return $service;
	}


	public function createServiceApplication__11(): NetteModule\MicroPresenter
	{
		$service = new NetteModule\MicroPresenter($this, $this->getService('http.request'),
			$this->getService('routing.router'));
		return $service;
	}


	public function createServiceApplication__2(): App\Presenters\DochadzkaPresenter
	{
		$service = new App\Presenters\DochadzkaPresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'), $this->getService('62_App_Model_Dochadzka'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__3(): App\Presenters\Error4xxPresenter
	{
		$service = new App\Presenters\Error4xxPresenter;
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__4(): App\Presenters\ErrorPresenter
	{
		$service = new App\Presenters\ErrorPresenter($this->getService('tracy.logger'));
		return $service;
	}


	public function createServiceApplication__5(): App\Presenters\HomepagePresenter
	{
		$service = new App\Presenters\HomepagePresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__6(): App\Presenters\OsobyPresenter
	{
		$service = new App\Presenters\OsobyPresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'), $this->getService('64_App_Model_Osoba'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__7(): App\Presenters\RFIDPresenter
	{
		$service = new App\Presenters\RFIDPresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'), $this->getService('65_App_Model_RFID'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__8(): App\Presenters\SignPresenter
	{
		$service = new App\Presenters\SignPresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__9(): App\Presenters\UserPresenter
	{
		$service = new App\Presenters\UserPresenter($this->getService('database.default.context'),
			$this->getService('66_App_Model_Uzivatel'));
		$service->injectPrimary($this, $this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'), $this->getService('session.session'),
			$this->getService('security.user'), $this->getService('latte.templateFactory'));
		$service->translator = $this->getService('translation.default');
		$service->invalidLinkMode = 5;
		return $service;
	}


	public function createServiceApplication__application(): Nette\Application\Application
	{
		$service = new Nette\Application\Application($this->getService('application.presenterFactory'),
			$this->getService('routing.router'), $this->getService('http.request'),
			$this->getService('http.response'));
		$service->catchExceptions = false;
		$service->errorPresenter = 'Error';
		Nette\Bridges\ApplicationTracy\RoutingPanel::initializePanel($service);
		$this->getService('tracy.bar')->addPanel(new Nette\Bridges\ApplicationTracy\RoutingPanel($this->getService('routing.router'),
			$this->getService('http.request'), $this->getService('application.presenterFactory')));
		$service->onRequest[] = [
			$this->getService('translation.userLocaleResolver.param'),
			'onRequest',
		];
		$self = $this; $service->onStartup[] = function () use ($self) { $self->getService('translation.default'); };
		$service->onRequest[] = [$this->getService('translation.panel'), 'onRequest'];
		return $service;
	}


	public function createServiceApplication__linkGenerator(): Nette\Application\LinkGenerator
	{
		$service = new Nette\Application\LinkGenerator($this->getService('routing.router'),
			$this->getService('http.request')->getUrl(), $this->getService('application.presenterFactory'));
		return $service;
	}


	public function createServiceApplication__presenterFactory(): Nette\Application\IPresenterFactory
	{
		$service = new Nette\Application\PresenterFactory(new Nette\Bridges\ApplicationDI\PresenterFactoryCallback($this, 5, 'C:\xampp\htdocs\sandbox\app/../temp/cache/Nette%5CBridges%5CApplicationDI%5CApplicationExtension'));
		$service->setMapping(['*' => 'App\*Module\Presenters\*Presenter']);
		return $service;
	}


	public function createServiceAuthenticator(): MyAuthenticator
	{
		$service = new MyAuthenticator($this->getService('database.default.context'));
		return $service;
	}


	public function createServiceCache__journal(): Nette\Caching\Storages\IJournal
	{
		$service = new Nette\Caching\Storages\SQLiteJournal('C:\xampp\htdocs\sandbox\app/../temp/cache/journal.s3db');
		return $service;
	}


	public function createServiceCache__storage(): Nette\Caching\IStorage
	{
		$service = new Nette\Caching\Storages\FileStorage('C:\xampp\htdocs\sandbox\app/../temp/cache',
			$this->getService('cache.journal'));
		return $service;
	}


	public function createServiceContainer(): Nette\DI\Container
	{
		return $this;
	}


	public function createServiceDatabase__default__connection(): Nette\Database\Connection
	{
		$service = new Nette\Database\Connection('mysql:host=127.0.0.1;dbname=nette', 'root',
			null, null);
		$this->getService('tracy.blueScreen')->addPanel('Nette\Bridges\DatabaseTracy\ConnectionPanel::renderException');
		Nette\Database\Helpers::createDebugPanel($service, true, 'default');
		return $service;
	}


	public function createServiceDatabase__default__context(): Nette\Database\Context
	{
		$service = new Nette\Database\Context($this->getService('database.default.connection'),
			$this->getService('database.default.structure'), $this->getService('database.default.conventions'),
			$this->getService('cache.storage'));
		return $service;
	}


	public function createServiceDatabase__default__conventions(): Nette\Database\Conventions\DiscoveredConventions
	{
		$service = new Nette\Database\Conventions\DiscoveredConventions($this->getService('database.default.structure'));
		return $service;
	}


	public function createServiceDatabase__default__structure(): Nette\Database\Structure
	{
		$service = new Nette\Database\Structure($this->getService('database.default.connection'),
			$this->getService('cache.storage'));
		return $service;
	}


	public function createServiceHttp__context(): Nette\Http\Context
	{
		$service = new Nette\Http\Context($this->getService('http.request'), $this->getService('http.response'));
		trigger_error('Service http.context is deprecated.', 16384);
		return $service;
	}


	public function createServiceHttp__request(): Nette\Http\Request
	{
		$service = $this->getService('http.requestFactory')->createHttpRequest();
		return $service;
	}


	public function createServiceHttp__requestFactory(): Nette\Http\RequestFactory
	{
		$service = new Nette\Http\RequestFactory;
		$service->setProxy([]);
		return $service;
	}


	public function createServiceHttp__response(): Nette\Http\Response
	{
		$service = new Nette\Http\Response;
		return $service;
	}


	public function createServiceLatte__latteFactory(): Nette\Bridges\ApplicationLatte\ILatteFactory
	{
		return new class ($this) implements Nette\Bridges\ApplicationLatte\ILatteFactory {
			private $container;


			public function __construct(Container_5080e879cd $container)
			{
				$this->container = $container;
			}


			public function create(): Latte\Engine
			{
				$service = new Latte\Engine;
				$service->setTempDirectory('C:\xampp\htdocs\sandbox\app/../temp/cache/latte');
				$service->setAutoRefresh(true);
				$service->setContentType('html');
				Nette\Utils\Html::$xhtml = false;
				$service->onCompile[] = function($engine) { Kdyby\Translation\Latte\TranslateMacros::install($engine->getCompiler()); };
				$service->addProvider('translator', $this->container->getService('translation.default'));
				$service->addFilter('translate', [$this->container->getService('translation.helpers'), 'translateFilterAware']);
				return $service;
			}
		};
	}


	public function createServiceLatte__templateFactory(): Nette\Application\UI\ITemplateFactory
	{
		$service = new Nette\Bridges\ApplicationLatte\TemplateFactory($this->getService('latte.latteFactory'),
			$this->getService('http.request'), $this->getService('security.user'),
			$this->getService('cache.storage'), null);
		return $service;
	}


	public function createServiceMail__mailer(): Nette\Mail\IMailer
	{
		$service = new Nette\Mail\SendmailMailer;
		return $service;
	}


	public function createServiceRouting__router(): Nette\Application\IRouter
	{
		$service = App\RouterFactory::createRouter();
		return $service;
	}


	public function createServiceSecurity__user(): Nette\Security\User
	{
		$service = new Nette\Security\User($this->getService('security.userStorage'), $this->getService('authenticator'));
		$this->getService('tracy.bar')->addPanel(new Nette\Bridges\SecurityTracy\UserPanel($service));
		return $service;
	}


	public function createServiceSecurity__userStorage(): Nette\Security\IUserStorage
	{
		$service = new Nette\Http\UserStorage($this->getService('session.session'));
		return $service;
	}


	public function createServiceSession__session(): Nette\Http\Session
	{
		$service = new Nette\Http\Session($this->getService('http.request'), $this->getService('http.response'));
		$service->setExpiration('14 days');
		$this->getService('tracy.bar')->addPanel(new Nette\Bridges\HttpTracy\SessionPanel);
		$service->setOptions(['name' => 'smartpas']);
		return $service;
	}


	public function createServiceTracy__bar(): Tracy\Bar
	{
		$service = Tracy\Debugger::getBar();
		return $service;
	}


	public function createServiceTracy__blueScreen(): Tracy\BlueScreen
	{
		$service = Tracy\Debugger::getBlueScreen();
		return $service;
	}


	public function createServiceTracy__logger(): Tracy\ILogger
	{
		$service = Tracy\Debugger::getLogger();
		return $service;
	}


	public function createServiceTranslation__catalogueCompiler(): Kdyby\Translation\CatalogueCompiler
	{
		$service = new Kdyby\Translation\CatalogueCompiler(new Kdyby\Translation\Caching\PhpFileStorage('C:\xampp\htdocs\sandbox\app/../temp/cache',
			$this->getService('cache.journal')), $this->getService('translation.fallbackResolver'),
			$this->getService('translation.catalogueFactory'));
		$service->enableDebugMode();
		return $service;
	}


	public function createServiceTranslation__catalogueFactory(): Kdyby\Translation\CatalogueFactory
	{
		$service = new Kdyby\Translation\CatalogueFactory($this->getService('translation.fallbackResolver'),
			$this->getService('translation.loader'));
		return $service;
	}


	public function createServiceTranslation__default(): Kdyby\Translation\Translator
	{
		$service = new Kdyby\Translation\Translator($this->getService('translation.userLocaleResolver'),
			$this->getService('translation.selector'), $this->getService('translation.catalogueCompiler'),
			$this->getService('translation.fallbackResolver'), $this->getService('translation.loader'));
		$this->getService('translation.userLocaleResolver.param')->setTranslator($service);
		$service->setDefaultLocale('en');
		$service->setLocaleWhitelist(['sk', 'en']);
		$service->setFallbackLocales(['en_US']);
		$this->getService('translation.panel')->register($service);
		$service->addResource('neon', 'C:\xampp\htdocs\sandbox\app\lang\menu.en_US.neon',
			'en_US', 'menu');
		$service->addResource('neon', 'C:\xampp\htdocs\sandbox\app\lang\menu.sk_SK.neon',
			'sk_SK', 'menu');
		$service->addResource('neon', 'C:\xampp\htdocs\sandbox\app\lang\reader.en_US.neon',
			'en_US', 'reader');
		$service->addResource('neon', 'C:\xampp\htdocs\sandbox\app\lang\reader.sk_SK.neon',
			'sk_SK', 'reader');
		$service->addResource('neon', 'C:\xampp\htdocs\sandbox\app\lang\ui.en_US.neon',
			'en_US', 'ui');
		$service->addResource('neon', 'C:\xampp\htdocs\sandbox\app\lang\ui.sk_SK.neon',
			'sk_SK', 'ui');
		return $service;
	}


	public function createServiceTranslation__dumper__csv(): Symfony\Component\Translation\Dumper\CsvFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\CsvFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__ini(): Symfony\Component\Translation\Dumper\IniFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\IniFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__mo(): Symfony\Component\Translation\Dumper\MoFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\MoFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__neon(): Kdyby\Translation\Dumper\NeonFileDumper
	{
		$service = new Kdyby\Translation\Dumper\NeonFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__php(): Symfony\Component\Translation\Dumper\PhpFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\PhpFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__po(): Symfony\Component\Translation\Dumper\PoFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\PoFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__qt(): Symfony\Component\Translation\Dumper\QtFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\QtFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__res(): Symfony\Component\Translation\Dumper\IcuResFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\IcuResFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__xliff(): Symfony\Component\Translation\Dumper\XliffFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\XliffFileDumper;
		return $service;
	}


	public function createServiceTranslation__dumper__yml(): Symfony\Component\Translation\Dumper\YamlFileDumper
	{
		$service = new Symfony\Component\Translation\Dumper\YamlFileDumper;
		return $service;
	}


	public function createServiceTranslation__extractor(): Symfony\Component\Translation\Extractor\ChainExtractor
	{
		$service = new Symfony\Component\Translation\Extractor\ChainExtractor;
		$service->addExtractor('latte', $this->getService('translation.extractor.latte'));
		return $service;
	}


	public function createServiceTranslation__extractor__latte(): Kdyby\Translation\Extractors\LatteExtractor
	{
		$service = new Kdyby\Translation\Extractors\LatteExtractor;
		return $service;
	}


	public function createServiceTranslation__fallbackResolver(): Kdyby\Translation\FallbackResolver
	{
		$service = new Kdyby\Translation\FallbackResolver;
		return $service;
	}


	public function createServiceTranslation__helpers(): Kdyby\Translation\TemplateHelpers
	{
		$service = $this->getService('translation.default')->createTemplateHelpers();
		return $service;
	}


	public function createServiceTranslation__loader(): Kdyby\Translation\TranslationLoader
	{
		$service = new Kdyby\Translation\TranslationLoader;
		$service->injectServiceIds([
			'php' => 'translation.loader.php',
			'yml' => 'translation.loader.yml',
			'xlf' => 'translation.loader.xlf',
			'po' => 'translation.loader.po',
			'mo' => 'translation.loader.mo',
			'ts' => 'translation.loader.ts',
			'csv' => 'translation.loader.csv',
			'res' => 'translation.loader.res',
			'dat' => 'translation.loader.dat',
			'ini' => 'translation.loader.ini',
			'json' => 'translation.loader.json',
			'neon' => 'translation.loader.neon',
		], $this);
		return $service;
	}


	public function createServiceTranslation__loader__csv(): Symfony\Component\Translation\Loader\CsvFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\CsvFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__dat(): Symfony\Component\Translation\Loader\IcuDatFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\IcuDatFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__ini(): Symfony\Component\Translation\Loader\IniFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\IniFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__json(): Symfony\Component\Translation\Loader\JsonFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\JsonFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__mo(): Symfony\Component\Translation\Loader\MoFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\MoFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__neon(): Kdyby\Translation\Loader\NeonFileLoader
	{
		$service = new Kdyby\Translation\Loader\NeonFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__php(): Symfony\Component\Translation\Loader\PhpFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\PhpFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__po(): Symfony\Component\Translation\Loader\PoFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\PoFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__res(): Symfony\Component\Translation\Loader\IcuResFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\IcuResFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__ts(): Symfony\Component\Translation\Loader\QtFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\QtFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__xlf(): Symfony\Component\Translation\Loader\XliffFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\XliffFileLoader;
		return $service;
	}


	public function createServiceTranslation__loader__yml(): Symfony\Component\Translation\Loader\YamlFileLoader
	{
		$service = new Symfony\Component\Translation\Loader\YamlFileLoader;
		return $service;
	}


	public function createServiceTranslation__panel(): Kdyby\Translation\Diagnostics\Panel
	{
		$service = new Kdyby\Translation\Diagnostics\Panel('C:\xampp\htdocs\sandbox');
		$service->setLocaleWhitelist(['sk', 'en']);
		$service->setLocaleResolvers([
			$this->getService('translation.userLocaleResolver.param'),
			$this->getService('translation.userLocaleResolver.acceptHeader'),
		]);
		return $service;
	}


	public function createServiceTranslation__selector(): Symfony\Component\Translation\MessageSelector
	{
		$service = new Symfony\Component\Translation\MessageSelector;
		return $service;
	}


	public function createServiceTranslation__userLocaleResolver(): Kdyby\Translation\IUserLocaleResolver
	{
		$service = new Kdyby\Translation\LocaleResolver\ChainResolver;
		$service->addResolver($this->getService('translation.userLocaleResolver.acceptHeader'));
		$service->addResolver($this->getService('translation.userLocaleResolver.param'));
		return $service;
	}


	public function createServiceTranslation__userLocaleResolver__acceptHeader(): Kdyby\Translation\LocaleResolver\AcceptHeaderResolver
	{
		$service = new Kdyby\Translation\LocaleResolver\AcceptHeaderResolver($this->getService('http.request'));
		return $service;
	}


	public function createServiceTranslation__userLocaleResolver__param(): Kdyby\Translation\LocaleResolver\LocaleParamResolver
	{
		$service = new Kdyby\Translation\LocaleResolver\LocaleParamResolver;
		return $service;
	}


	public function createServiceTranslation__userLocaleResolver__session(): Kdyby\Translation\LocaleResolver\SessionResolver
	{
		$service = new Kdyby\Translation\LocaleResolver\SessionResolver($this->getService('session.session'),
			$this->getService('http.response'));
		return $service;
	}


	public function createServiceTranslation__writer(): Symfony\Component\Translation\Writer\TranslationWriter
	{
		$service = new Symfony\Component\Translation\Writer\TranslationWriter;
		$service->addDumper('php', $this->getService('translation.dumper.php'));
		$service->addDumper('xliff', $this->getService('translation.dumper.xliff'));
		$service->addDumper('po', $this->getService('translation.dumper.po'));
		$service->addDumper('mo', $this->getService('translation.dumper.mo'));
		$service->addDumper('yml', $this->getService('translation.dumper.yml'));
		$service->addDumper('neon', $this->getService('translation.dumper.neon'));
		$service->addDumper('qt', $this->getService('translation.dumper.qt'));
		$service->addDumper('csv', $this->getService('translation.dumper.csv'));
		$service->addDumper('ini', $this->getService('translation.dumper.ini'));
		$service->addDumper('res', $this->getService('translation.dumper.res'));
		return $service;
	}


	public function initialize()
	{
		date_default_timezone_set('Europe/Prague');
		$this->getService('tracy.bar')->addPanel(new Nette\Bridges\DITracy\ContainerPanel($this));
		$this->getService('http.response')->setHeader('X-Powered-By', 'Nette Framework');
		$this->getService('http.response')->setHeader('Content-Type', 'text/html; charset=utf-8');
		$this->getService('http.response')->setHeader('X-Frame-Options', 'SAMEORIGIN');
		$this->getService('session.session')->exists() && $this->getService('session.session')->start();
		Tracy\Debugger::$editorMapping = [];
		Tracy\Debugger::setLogger($this->getService('tracy.logger'));
		if ($tmp = $this->getByType("Nette\Http\Session", false)) { $tmp->start(); Tracy\Debugger::dispatch(); };
		Kdyby\Translation\Diagnostics\Panel::registerBluescreen();
	}
}
