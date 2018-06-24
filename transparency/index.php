<?php

$root = dirname(__DIR__);

$base_path = '/transparency';
$url = "https://onbehalfof.life$base_path/";
$comment_url = 'https://www.regulations.gov/comment?D=EPA-HQ-OA-2018-0259-0001';

$species_list = 'transparency';
$campaign = 'transparency';

$call_to_action = 'tell the U.S. EPA: <strong>don’t weaponize transparency in science</strong>';
$intro = "On behalf of ALL LIFE, we demand that the US EPA offers environmental protection and climate justice for ALL LIFE.";
$deadline = 'August 16, 2018';

$example_title = 'Princess Tree (<i>Paulownia tomentosa</i>)';
$example_quote = 'I am writing to you on behalf of Princess Tree (Paulownia tomentosa), air toxicology consultant to the Department of Weedy Affairs. On behalf of Princess Tree and its many allies in the multispecies community of the Bronx, I write to strongly condemn your attempt to limit the scientific data that can be used by the EPA in creating environmental regulations. Princess Tree is very familiar with the polluted conditions of air alongside expressways and highways in the Bronx, as it gathers and sequesters particulate matter on its large, fuzzy leaves. Many of these particles are of the type that elevate asthma levels in the human population that lives alongside Princess Tree. The Six Cities Study carried out by Harvard in the 1990s was essential in proving to the US Environmental Protection Agency what plants like Princess Tree already know: air pollution is connected to premature death in humans (and plants!). These premature deaths in humans are felt more acutely by poor communities of color than other groups. Your plan to limit the data used by the EPA in making regulations around environmental justice issues like air pollution is a weaponization of the concept of transparency in science. Rather than limiting the range of experts we call upon to draft environmental regulations, we must expand our notion of expertise, calling on experts like Princess Tree, which thrives in harsh urban conditions and labors daily to reduce particulate matter, cool the air, produce oxygen, and stabilize soil with its large root system. Princess tree is the kind of expert who’s data we must heed in the midst of the 6th extinction, as we strive to make more space for life, human and beyond.';

$more_info = <<<END
<h2>More info</h2>
<ul>
	<li>The New Yorker: <a href="https://www.newyorker.com/science/elements/scott-pruitts-crusade-against-secret-science-could-be-disastrous-for-public-health">Scott Pruitt’s crusade against “secret science” could be disastrous for public health</a></li>
	<li>The New York Times: <a href="https://www.nytimes.com/2018/04/24/climate/epa-science-transparency-pruitt.html">New Rule will likely have one result: Less Science in policy making</a></li>
	<li>The New York Times: <a href="https://www.nytimes.com/2018/03/26/climate/epa-scientific-transparency-honest-act.html">EPA says it wants research transparency. Scientists see an attack on science.</a>
	<li>Environmental Defense Fund: <a href="http://blogs.edf.org/health/2018/05/01/new-epa-science-regulation-a-trojan-horse-that-hurts-public-health/">New EPA Science Regulation: A Trojan Horse that Hurts Public Health</a></li>
	<li>The Hill: <a href="http://thehill.com/policy/energy-environment/389171-epa-extends-comment-period-on-controversial-science-transparency">EPA extends comment period on controversial rule</a></li>
	<li>The Washington Post: <a href="https://www.washingtonpost.com/news/energy-environment/wp/2018/04/24/pruitt-to-unveil-controversial-transparency-rule-limiting-what-research-epa-can-use/">Pruitt Unveils Controversial ‘transparency’ rule limiting what research EPA can use</a></li>
	<li><i>Science</i> Editor in Chief: <a href="science.aau0116.full.pdf">Joint statement on EPA proposed rule and public availability of data</a> (pdf)</li>
</ul>
END;

// Facebook card
$og_title = 'ON BEHALF OF LIFE';
$og_description = ucfirst(strip_tags($call_to_action));
$og_image = "https://onbehalfof.life$base_path/card.jpg";
$og_url = $url;

// Twitter card
$twitter_title = $og_title;
$twitter_description = $og_description;
$twitter_image = $og_image;
$twitter_url = $url;

require_once '../template.php';
