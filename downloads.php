<?php
// set the format for badges and warnings (if required later on)
$recommendedBadge = "<span class=\"badge badge-success\">Recommended</span>";
$prereleaseBadge = "<span class=\"badge badge-warning\">Pre-Release</span>";
$prereleaseWarn = "<div class=\"alert alert-warning\" role=\"alert\">WARNING: This is a pre-release version, meaning it's unfinished and may not work as intended. Unless you know what you're doing, it's best to download the latest public release instead.</div>";

// specify data source
$versionSheet = "https://docs.google.com/spreadsheets/d/e/2PACX-1vS0ka65eWKugM_Ev_AQA1Whcg4_cAXiMmOcC5RWbVTROXhyC1eVMVas83OKObzbgbzjgQ9_NaiCrhnh/pub?gid=0&single=true&output=csv";
$runtimeSheet = "https://docs.google.com/spreadsheets/d/e/2PACX-1vS0ka65eWKugM_Ev_AQA1Whcg4_cAXiMmOcC5RWbVTROXhyC1eVMVas83OKObzbgbzjgQ9_NaiCrhnh/pub?gid=1057688818&single=true&output=csv";

// convert the versions to an array, remove the first row and flip the order so newest versions appear at the top
$versioncsv = array_map('str_getcsv', file($versionSheet));
array_walk($versioncsv, function(&$a) use ($versioncsv) {
	$a = array_combine($versioncsv[0], $a);
});
array_shift($versioncsv);
$versioncsv = array_reverse($versioncsv, true);

// same for runtimes
$runtimecsv = array_map('str_getcsv', file($runtimeSheet));
array_walk($runtimecsv, function(&$a) use ($runtimecsv) {
	$a = array_combine($runtimecsv[0], $a);
});

array_shift($runtimecsv);
$runtimecsv = array_reverse($runtimecsv, true);

// generate basic html for version list
$downloadList = "<div class=\"container accordion\" id=\"downloadList\">\n";
foreach ($versioncsv as $k => $v) {
	$btnColor = "primary";
	$item = $v[n];
	$rel = $v[Release];
	$date = $v[Date];
	$runtime = $v[Runtime];
	$badge = ""; $warn = "";
	if ($v[rec] == "TRUE") { $badge = $recommendedBadge; }
	if ($v[pre] == "TRUE") { $badge = $prereleaseBadge; $warn = $prereleaseWarn; $btnColor = "warning";}
	$file = "https://github.com/AOF-Dev/MCinaBox/releases/download/$rel/$v[file]";
	$source = "https://github.com/AOF-Dev/MCinaBox/tree/$rel";
	$notes = $v[Notes];

	$downloadList .= "
	<div class=\"card\">
		<div class=\"card-header\" id=\"header$item\">
			<h2 class=\"mb-0\">
			<button class=\"btn btn-block text-left\" type=\"button\" data-toggle=\"collapse\" data-target=\"#info$item\" aria-expanded=\"true\" aria-controls=\"info$item\">
			$badge <span class=\"h6\">$rel</span> <span class=\"text-muted\">$date</span> <span class=\"badge badge-dark\">$runtime</span>
			</button>
			</h2>
		</div>
	
		<div id=\"info$item\" class=\"collapse\" aria-labelledby=\"header$item\" data-parent=\"#downloadList\">
			<div class=\"card-body text-left\">
				$warn
				$notes
				<a class=\"btn btn-$btnColor\" href=\"$file\" role=\"button\">Download APK</a>
				<a class=\"btn btn-outline-secondary\" href=\"$source\" target=\"_blank\" role=\"button\">View Source</a>
			</div>
		</div>
	</div>
";
	} $downloadList .= "</div>";

//generate html for runtime list
$runtimeList = "<div class=\"container accordion\" id=\"runtimeList\" style=\"padding-bottom: 64px;\">";

foreach ($runtimecsv as $k => $v) {
	$ver = $v[R];
	$info = $v[Info];
	$aarch32 = ""; $aarch64 = "";
	if ($v[aarch32] !== "") { $aarch32 = "<a class=\"btn btn-primary\" href=\"$v[aarch32]\" role=\"button\">Download (aarch32)</a>"; }
	if ($v[aarch64] !== "") { $aarch64 = "<a class=\"btn btn-primary\" href=\"$v[aarch64]\" role=\"button\">Download (aarch64)</a>"; }
	
	$runtimeList .= "
	<div class=\"card\">
		<div class=\"card-header\" id=\"runtime$ver\">
			<h2 class=\"mb-0\">
			<button class=\"btn btn-block text-left\" type=\"button\" data-toggle=\"collapse\" data-target=\"#inforuntime$ver\" aria-expanded=\"true\" aria-controls=\"inforuntime$ver\">
			<span class=\"h6\">Runtime Pack $ver</span>
			</button>
			</h2>
		</div>
	
		<div id=\"inforuntime$ver\" class=\"collapse\" aria-labelledby=\"runtime$ver\" data-parent=\"#runtimeList\">
			<div class=\"card-body text-left\">
				$info
				$aarch32
				$aarch64
			</div>
		</div>
	</div>
"; } $runtimeList .= "</div>";

?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<title>Download MCinaBox</title>
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-62831205-9"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-62831205-9');
		</script>
	</head>
	<body class="bg-light">
		<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href=".">MCinaBox Releases</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item">
						<a class="nav-link" href=".">About</a>
					</li>
					<li class="nav-item active">
						<a class="nav-link" href="downloads"><span class="sr-only">(current)</span>Downloads</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://github.com/AOF-Dev/MCinaBox">GitHub</a>
					</li>
				</ul>
			</div>
		</nav>

		<div class="bg-light text-center">
			<h1 style="padding-top:32px">Versions</h1>
			<p>For installation instructions see video tutorials on the homepage. Scroll down to find required runtime files.</p>
			
			<?php echo $downloadList; ?>
			
			<h1 style="padding-top:32px">Runtime Packs</h1>
			<p>Runtime packs are required to launch Minecraft. Make sure you <b>download the pack compatible with the version of the app you are using!</b><br>
			If you don't know how to install the runtime pack, watch the tutorials on the homepage</p>
			
			<?php echo $runtimeList; ?>
			
			<p>Download list generated on <?php echo date("Y/m/d");?>. <a href="https://docs.google.com/spreadsheets/d/e/2PACX-1vS0ka65eWKugM_Ev_AQA1Whcg4_cAXiMmOcC5RWbVTROXhyC1eVMVas83OKObzbgbzjgQ9_NaiCrhnh/pubhtml" target="_blank">View raw data</a></p>
			
		</div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	</body>
</html>