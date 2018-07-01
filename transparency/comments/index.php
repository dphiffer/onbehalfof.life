<?php

include dirname(dirname(__DIR__)) . '/config.php';

$og_title = 'Public comments ON BEHALF OF LIFE';
$og_description = "We demand that the US EPA offers environmental protection and climate justice for ALL LIFE.";

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Public Comment Submissions ON BEHALF OF LIFE</title>
		<link rel="stylesheet" href="/mensch/font.css">
		<link rel="stylesheet" href="/life.css?6">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Environmental Performance Agency">
		<meta name="copyright" content="Licensed under GPL and MIT.">
		<meta name="description" content="The Environmental Performance Agency (EPA) is an artist collective founded in 2017 and named in response to the proposed defunding of the U.S. Environmental Protection Agency.">
		<meta property="og:title" content="<?php echo htmlentities($og_title); ?>">
		<meta property="og:description" content="<?php echo htmlentities($og_description); ?>">
		<meta property="og:image" content="<?php echo htmlentities($og_image); ?>">
		<meta property="og:url" content="<?php echo htmlentities($og_url); ?>">
		<meta property="og:type" content="website">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?php echo htmlentities($twitter_title); ?>">
		<meta name="twitter:description" content="<?php echo htmlentities($twitter_description); ?>">
		<meta name="twitter:image" content="<?php echo htmlentities($twitter_image); ?>">
	</head>
	<body>
		<div class="container">
			<h1>Public Comment Submissions On Behalf of Life</h1>
			<div id="public-comments" data-campaign="transparency" data-url="<?php echo $config['deliverator_url']; ?>"></div>
		</div>
		<script src="/jquery.min.js"></script>
		<script src="/comments.js?1"></script>
	</body>
</html>
