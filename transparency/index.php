<?php

$root = dirname(__DIR__);

$base_path = '/transparency';
$url = "https://onbehalfof.life$base_path/";
$comment_url = 'https://www.regulations.gov/comment?D=EPA-HQ-OA-2018-0259-0001';
$video_embed = '<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/Fch420RtqU0?rel=0&amp;showinfo=0&amp;ecver=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';

$species_list = 'transparency';
$campaign = 'transparency';

$call_to_action = 'tell the U.S. EPA: <strong>don’t weaponize transparency</strong>';
$intro = "On behalf of ALL LIFE, we demand that the US EPA offers environmental protection and climate justice for ALL LIFE.";
$deadline = 'August 16, 2018';

$short_example = 'On behalf of Paulownia (Princess tree) and its multispecies allies across the Bronx, we decry your plan to limit the use of public health data by the US EPA. Your proposed rule is a gross distortion of the call for transparency in science, and if approved, will have dire environmental justice consequences. Listen to Paulownia, an air toxicity specialist that labors in harsh urban conditions to reduce particulate matter, cool the air, and produce oxygen. Don’t use transparency in science as a trojan horse to bury public health research. Paulownia says let in the light and air, but don’t weaponize transparency! <i><a href="#worksheet">Check out the worksheet below for ideas</a></i>';

$example_title = 'Princess Tree (<i>Paulownia tomentosa</i>)';
$example_quote = 'On behalf of Paulownia (Princess tree) and its multispecies allies across the Bronx, we decry your plan to limit the use of public health data by the US EPA. Your proposed rule is a gross distortion of the call for transparency in science, and if approved, will have dire environmental justice consequences. Paulownia is very familiar with the polluted conditions of air along roadways in the Bronx, as it collects particulate matter on its large, fuzzy leaves. The Six Cities Study (air pollution research carried out in the 1990s) provided essential evidence proving what plants like Paulownia already know: air pollution is connected to premature death in humans (and plants!). These premature deaths impact poor communities of color more than other groups. Your rule would discount this important study and others like it. Rather than limiting the information used to draft environmental regulations, let’s expand our notion of expertise. Listen to Paulownia, an air toxicity specialist that labors in harsh urban conditions to reduce particulate matter, cool the air, and produce oxygen. Don’t use transparency in science as a trojan horse to bury public health research. Paulownia says let in the light and air, but don’t weaponize transparency!';

$instructions = <<<END
<h2 id="worksheet"><a href="/transparency/handout.pdf">Download this worksheet PDF</a></h2>
<a href="/transparency/handout.pdf"><img src="/transparency/handout-pg1.jpg" class="handout"></a>
<a href="/transparency/handout.pdf"><img src="/transparency/handout-pg2.jpg" class="handout pg2"></a>
<br class="clear">
END;

$more_info = <<<END
<h2>More info</h2>
<blockquote><p>"The pending E.P.A. policy would have implications for much of what the agency touches, whether it is new rules addressing climate change or regulations for pesticides and protecting children from lead paint.</p>

<p>“This affects every aspect of environmental protection in the United States,” said David Michaels, assistant secretary of labor for occupational safety and health under President Barack Obama. Mr. Michaels, now a professor at George Washington University, called the plan “weaponized transparency.”"</p></blockquote>
<p>&mdash;<a href="https://www.nytimes.com/2018/03/26/climate/epa-scientific-transparency-honest-act.html">The New York Times</a> (March 26, 2018)</p>
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
