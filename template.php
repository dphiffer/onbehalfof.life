<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>ON BEHALF OF LIFE</title>
		<link rel="stylesheet" href="/mensch/font.css">
		<link rel="stylesheet" href="/life.css?3">
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
			<h1>On behalf of <span id="life">all life</span>, <?php echo $call_to_action; ?></h1>
			<div class="columns">
				<div id="intro"><?php echo $intro; ?></div>
				<a id="button" href="<?php echo $comment_url; ?>" target="_blank">Submit a public comment</a>
				<div id="deadline">The deadline is <?php echo $deadline; ?>.</div>
				<a href="#" id="reload">Pick another species</a>
			</div>
			<div class="columns" id="share-img"></div>
			<br class="clear">
			<div id="example">
				<h2>Example: <b><?php echo $example_title; ?></b></h2>
				<blockquote>
					<?php echo $example_quote; ?>
				</blockquote>
			</div>
			<div id="onbehalfof">
				<a href="https://www.regulations.gov/comment?D=EPA-HQ-OA-2017-0533-0219" target="_blank"><img src="/media/onbehalfof.jpg" alt="On behalf of..."></a>
			</div>
			<h2><a href="#statement"><i>Read more example text below</i></a></h2>
		</div>
		<div id="mugwort">
			<h1><span>On Behalf of <strong>Artemisia vulgaris</strong>, on behalf of <strong>ALL LIFE</strong>, <?php echo $call_to_action; ?></span></h1>
			<video src="/media/mugwort.mp4" poster="/media/poster.jpg" autoplay="1" loop="1"></video>
		</div>
		<div id="share" class="container">
			<h2>Shareable images to help spread the word</h2>
			<a href="example1.jpg"><img src="example1.jpg" alt="www.onbehalfof.life"></a>
			<div class="template">
				<a href="template.png"><img src="template.png" alt="www.onbehalfof.life"></a>
			</div>
			<a href="example2.png"><img src="example2.png" alt="www.onbehalfof.life"></a>
			<br class="clear">
			<p><a href="<?php echo $base_path; ?>/template.png">Download the template to add your own background image.</a> (<a href="<?php echo $base_path; ?>/template-inverted.png">Inverted text version</a>)</p>
			<div id="custom"></div>
		</div>
		<div id="statement" class="container">
			<h2>Statement from the <a href="http://www.environmentalperformanceagency.com/">Environmental Performance Agency</a></h2>
			<em>If any or all of this resonates with you, feel free to copy and paste in your <a href="<?php echo $comment_url; ?>" target="_blank">Public Comment</a>.</em>
			<p>We believe the US EPA has an obligation to preserve and support the atmosphere, lithosphere, biosphere and all the other spheres of life both human and nonhuman for all present and future generations and the undersigned species and their ecosystem partners.</p>
			<p>We believe the survival of all life is threatened by the degradation of the environment and increased changes to the climate as evidenced by increasingly frequent wildfires, sea level rise, drought, and flood events. We believe public-private partnerships (corporate cronyism) are asymmetrical and benefit private partners over the health of the public, protection of which is the explicit mission of the US EPA. Despite knowledge of climate change impacts, US EPA’s inaction perpetuates the degradation of the environment, threatening all existing species with ecosystem and food system collapse.</p>
			<p>We believe in the agency of all life forms, and believe the US EPA should create equitable spaces for life to thrive in a time of extinction. To do this, the US EPA strategic plan must reflect the diverse needs of a changing ecosystem in the US and its territories Puerto Rico and the US Virgin Islands&mdash;and not of the private industries that continue to exploit and ravage these communities.</p>
			<p>The US EPA’s core mission should deliver real results, which should include ban of toxic chemicals, support of healthy soil practices, removal of pesticides from agricultural use, clean water without lead, divestment from fossil fuels and the rejection of current and proposed oil pipeline infrastructure.</p>
			<p>Yes let’s rebalance the power between Washington, the states, and the people. Not only for the American People but for the planet.</p>
			<p>The US EPA should administer the law and refocus the Agency toward climate change legislation, enforcement and resiliency planning&mdash;to ensure the integrity of all life forms on the planet.</p>
			<?php echo $more_info; ?>
			<h2>On behalf of</h2>
			<div id="all-species"></div>
		</div>
		<script src="/jquery.min.js"></script>
		<script src="/life.js?2"></script>
	</body>
</html>
