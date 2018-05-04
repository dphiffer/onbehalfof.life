<?php

$root = dirname(__DIR__);

include_once("$root/twilio.php");

$base_path = '/weedy-affairs';
$url = "https://onbehalfof.life$base_path/";
//$comment_url = 'https://www.regulations.gov/comment?D=EPA-HQ-OPP-2011-0865-0250';

$call_to_action = 'CALL OR TEXT <strong>(240) 808-2372</strong>';
$intro = "On behalf of ALL LIFE, we demand that the US EPA offers environmental protection and climate justice for ALL LIFE.";
$deadline = 'June 14, 2018';

function get_comment_html($comment) {
	global $comment_count;

	if (empty($comment->visible)) {
		return '';
	}

	if (empty($comment_count)) {
		$comment_count = 1;
	} else {
		$comment_count++;
	}

	$html = '';

	$class = ($comment_count > 3) ? ' more-comments' : '';
	if ($comment_count == 4) {
		$html .= '<a href="#more-comments" id="more-comments">Show more comments</a>';
	}

	$phone = preg_replace('/^\+\d+(\d{4})$/', 'xxx-xxx-$1', $comment->phone);
	$html .= "<div class=\"public-comment $comment->type $class\">\n";
	if ($comment->type == 'sms') {
		$html .= "<h3>SMS from $phone</h3>\n";
		$html .= "<div class=\"comment-message\">$comment->message</div>\n";
	} else if ($comment->type == 'recording') {
		$html .= "<h3>Call from $phone</h3>\n";
		if (! empty($comment->message)) {
			$html .= "<div class=\"comment-message\">$comment->message</div>\n";
		}
		$html .= "<audio src=\"/twilio/$comment->audio\" type=\"audio/mpeg\" controls></audio>\n";
	}
	$html .= "</div>\n";

	return $html;
}

$comments = twilio_get_comments();
$comments_html = '';
foreach ($comments as $comment) {
	$comments_html .= get_comment_html($comment);
}

if (empty($comments_html)) {
	$comments_html = '(no public comments yet)';
}

$public_comments = <<<END
<div id="public-comments">
	<h2>Public Comments</h2>
	$comments_html
</div>
END;

//$example_title = 'European praying mantis (<i>Mantis religiosa</i>)';
//$example_quote = 'On behalf of the European praying mantis, <i>Mantis religiosa</i>, I demand the EPA reconsider its decision to allow the continued use of neonicotinoid pesticides. Like many pesticides, these substances leak into the ecosystem far beyond agricultural fields and their initial target organisms. Like other systemic pesticides, they are absorbed into the flesh of plants, spreading neurotoxic poisons throughout their tissues, including the sap, nectar and pollen. These are then taken up by insects (like the beneficial and elegant mantis!) and birds, building up in their bodies through repeated exposure, a cumulative and irreversible process. Europe has a temporary ban in place and is moving towards a permanent one as evidence mounts against these pesticides. The U.S. EPA’s own research shows it would be advisable to do the same here. While the EPA waits to act, widespread use continues, producing “severe effects on a range of organisms that provide ecosystem services like pollination and natural pest control, as well as on biodiversity” according to a 2015 report by the European Academies Science Advisory Council. There are enough toxic substances cycling in our environment, incrementally poisoning organisms large and small. On behalf of <i>Mantis religiosa</i>, I demand an end to this pernicious cycle.';

$below_text = <<<END
<p><strong>Onbehalfof.life</strong> is a web platform for submitting public comments to the US Environmental Protection Agency on behalf of another species. The US EPA is a governmental agency that is required to accept public comments, specifically whenever a new environmental regulation is passed or amended—for example when chemical pesticides are deemed “safe” to use. The aim of this web tool is to foster public commentary that articulates a vision for environmental justice on behalf of all life, challenging the current administration's self-serving disregard for climate and the environment. <strong>Onbehalfof.life</strong> is periodically updated to reflect the current issues that are open to public comment at the US EPA. Submitted comments will be posted to the US EPA and become part of a permanent record.</p>

<p>From May 5th–June 16th, Onbehalfof.life is hosting a special project: <a href="http://www.environmentalperformanceagency.com/weedyaffairs/">The Environmental Performance Agency’s <strong>Department of Weedy Affairs</strong></a>. We invite you to raise your voice! What do you expect from a government agency tasked with protecting “the environment”? The US Environmental Protection Agency is being dismantled before our very eyes: Since the appointment of Scott Pruitt in 2017, the US EPA has removed nearly all mention of climate change from its website and strategic plan, has censored its own scientists, and has <a href="https://www.nytimes.com/interactive/2017/10/05/climate/trump-environment-rules-reversed.html">overturned 33+ rules and rolled back dozens of initiatives</a> that protect human <em>and</em> more-than-human health.</p>

<p>In response, we invite you to call or text the Environmental Performance Agency’s hotline and leave a message for Scott Pruitt and US EPA officials on behalf of a life form who can’t. Let them know what you think about these changes, and help us imagine a governmental agency that advocates for ecological justice in a multispecies entangled world. We invite you to speak from the perspective of another species to highlight multi-species empathy and ecological interdependence of all life. Your contribution will be delivered in person on June 15, 2018 to the US Environmental Protection Agency’s headquarters in Washington DC and displayed here on this website.</p>

<p>In Weedy Solidarity,<br>
The Environmental Performance Agency</p>

<p>CALL OR TEXT (240) 808-2372</p>
END;

$more_info = <<<END
<h2>More info</h2>
<ul>
	<li><a href="https://www.cnn.com/2017/12/30/politics/environmental-policy-moments-2017/index.html">5 major changes to US environmental policy in 2017</a></li>
	<li><a href="https://www.nytimes.com/interactive/2017/10/05/climate/trump-environment-rules-reversed.html">67 Environmental Rules on the Way Out Under Trump</a></li>
	<li><a href="http://www.newsweek.com/scott-pruitt-personally-oversaw-efforts-erase-climate-change-information-epa-798069">Pruitt Directly Oversaw Efforts to Erase Climate CHange Info from EPA Website, Emails Reveal</a></li>
	<li><a href="http://time.com/5075265/epa-website-climate-change-censorship/">Here's What the EPA's Website Looks Like After a Year of Climate Change Censorship</a></li>
	<li><a href="https://www.theguardian.com/environment/climate-consensus-97-per-cent/2018/feb/12/the-epa-debunked-administrator-pruitts-latest-climate-misinformation">The EPA debunked Administrator Pruitt’s latest climate misinformation</a></li>
	<li><a href="http://time.com/4998279/company-man-in-washington/">Inside Scott Pruitt’s Mission to Remake the EPA</a></li>
	<li><a href="https://19january2017snapshot.epa.gov/climatechange_.html">Archived version of the US EPA’s Climate Change page</a></li>
	<li><a href="https://archive.org/details/aj10017_hotmail_EPA">Archive of entire former EPA website as a 50GB download</a></li>
	<li><a href="https://climatemirror.org/">Climate Mirror: An independent project to back up climate science in the Trump era</a></li>
	<li>More projects to specifically address climate change deniers and create a refuge for data:
		<ul>
			<li><a href="https://www.datarefuge.org/">datarefuge.org</a></li>
			<li><a href="http://www.ppehlab.org/datarefuge">ppehlab.org/datarefuge</a></li>
			<li><a href="https://envirodatagov.org/">envirodatagov.org</a></li>
		</ul>
	</li>
</ul>
END;

// Facebook card
$og_title = 'ON BEHALF OF LIFE';
$og_description = ucfirst($call_to_action);
$og_image = "https://onbehalfof.life$base_path/card.jpg";
$og_url = $url;

// Twitter card
$twitter_title = $og_title;
$twitter_description = $og_description;
$twitter_image = $og_image;
$twitter_url = $url;

require_once '../template.php';
