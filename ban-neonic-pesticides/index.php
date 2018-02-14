<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$base_path = '/ban-neonic-pesticides';
$url = "https://onbehalfof.life$base_path/";
$comment_url = 'https://www.regulations.gov/comment?D=EPA-HQ-OPP-2011-0865-0250';

$call_to_action = 'it’s time to Ban Neonic Pesticides!';
$intro = "On behalf of ALL LIFE, we demand an end to the large scale use of neonicotinoid pesticides in U.S. agriculture. Like many pesticides, they leak into the ecosystem far beyond agricultural fields and their target organisms. Even <span id=\"another\">one other species</span> can be effected by this toxic cycle, as can you and I! Submit a public comment to the <a href=\"$comment_url\">Registration Review EPA-HQ-OPP-2017-0011-0006</a> on behalf of an organism that can’t. Submit a comment on behalf of all life!";
$deadline = 'February 20, 2018';

$example_title = 'European praying mantis (<i>Mantis religiosa</i>)';
$example_quote = 'On behalf of the European praying mantis, <i>Mantis religiosa</i>, I demand the EPA reconsider its decision to allow the continued use of neonicotinoid pesticides. Like many pesticides, these substances leak into the ecosystem far beyond agricultural fields and their initial target organisms. Like other systemic pesticides, they are absorbed into the flesh of plants, spreading neurotoxic poisons throughout their tissues, including the sap, nectar and pollen. These are then taken up by insects (like the beneficial and elegant mantis!) and birds, building up in their bodies through repeated exposure, a cumulative and irreversible process. Europe has a temporary ban in place and is moving towards a permanent one as evidence mounts against these pesticides. The U.S. EPA’s own research shows it would be advisable to do the same here. While the EPA waits to act, widespread use continues, producing “severe effects on a range of organisms that provide ecosystem services like pollination and natural pest control, as well as on biodiversity” according to a 2015 report by the European Academies Science Advisory Council. There are enough toxic substances cycling in our environment, incrementally poisoning organisms large and small. On behalf of <i>Mantis religiosa</i>, I demand an end to this pernicious cycle.';

$more_info = <<<END
<h2>More info</h2>
<ul>
<li><a href="https://www.newscientist.com/article/2149597-neonicotinoid-pesticides-found-in-honey-from-every-continent/">Neonicotinoid pesticides found in honey from every continent</a>, New Scientist, October 2017</li>
<li><a href="https://www.theguardian.com/environment/2017/nov/09/uk-will-back-total-ban-on-bee-harming-pesticides-michael-gove-reveals">UK will back total ban on bee-harming pesticides, Michael Gove reveals</a>, The Guardian, July 2017</li>
<li><a href="http://www.biologicaldiversity.org/news/press_releases/2018/pesticides-02-13-2018.php">Congressman Blumenauer, Conservation Groups to Hold Press Conference Wednesday to Urge EPA to Suspend Approval of Pollinator-killing Pesticides</a>, Center for Biological Diversity, February 2017</li>
<li><a href="https://www.nytimes.com/2015/04/09/business/energy-environment/pesticides-probably-more-harmful-than-previously-thought-scientist-group-warns.html">Pesticides Linked to Honeybee Deaths Pose More Risks, European Group Says</a>, NY Times, 2015</li>
</ul>
END;

// Facebook card
$og_title = 'ON BEHALF OF LIFE';
$og_description = $call_to_action;
$og_image = "https://onbehalfof.life$base_path/card.jpg";
$og_url = $url;

// Twitter card
$twitter_title = $og_title;
$twitter_description = $og_description;
$twitter_image = $og_image;
$twitter_url = $url;

require_once '../template.php';
