<?php

namespace App\Service\Handler\UserSolution;

use App\Entity\UserSolution;

class UserSolutionHandler implements UserSolutionHandlerInterface
{
    private const CATEGORY = 'Votre situation';

    public function __invoke(UserSolution $userSolution): array
    {
        $advices = [];

        switch ($userSolution->getCategory()) {
            case 'anxiety':
                $advices[self::CATEGORY] = "
                    <p>Les troubles anxieux peuvent devenir un vrai calvaire au quotidien. Peur constante, anticipation pessimiste, angoisse ou encore attaque de panique sont des symptômes qui peuvent être très invalidants.</p>
                    <p>Si ce n'est pas déjà fait, il est alors important de consulter au moins votre médecin traitant. Ce dernier pourra vous faire une première auscultation et vous donner les premiers conseils.</p>
                    <p>
                        Envisagez également de consulter un professionnel de la santé mentale. Un psychologue ou un psychiatre pourra vous aider à comprendre ce que vous vivez et vous donner des outils pour vous en sortir.
                    </p>
                    <p>Au-delà de la médication, qui n'est pas automatiquement nécessaire, il existe des thérapies qui ont fait leurs preuves, comme la Thérapie Comportementale et Cognitive (TCC)</p>
                    ";
                break;
            case 'depression':
                $advices[self::CATEGORY] = "
                    <p>La dépression peut devenir un vrai calvaire au quotidien. L'important est de ne pas la laisser gagner du terrain et de mettre en place les bonnes actions pour aller mieux.</p>
                    <p>Si ce n'est pas déjà fait, il est alors important de consulter au moins votre médecin traitant. Ce dernier pourra vous faire une première auscultation et vous donner les premiers conseils.</p>
                    <p>Envisagez également de consulter un professionnel de la santé mentale. Un psychologue ou un psychiatre pourra vous aider à comprendre ce que vous vivez et vous donner des outils pour vous en sortir.</p>
                    ";
                break;
            case 'bipolar':
                $advices[self::CATEGORY] = "
                    <p>Le trouble bipolaire est une atteinte psychique très particulière, se caractérisant par plusieurs phases d'humeur.</p>
                    <p>L'accompagnement et le suivi dans la durée par un psychologue ou un psychiatre est important afin de stabiliser l'état et gérer au mieux les différentes phases.</p>
                    ";
                break;
            case 'personality':
                $advices[self::CATEGORY] = "
                    <p>Le trouble bipolaire est une atteinte psychique complexe, qui peut se manifester de manière différente chez chaque individu.</p>
                    <p>L'accompagnement et le suivi dans la durée par un psychologue ou un psychiatre est important afin d'avoir un diagnostic précis, et ainsi mettre en place les meilleures solutions pour aller mieux.</p>
                    <p>La thérapie comportementale dialectique (TCD) et la thérapie cognitivo-comportementale (TCC) sont parmi les approches thérapeutiques les plus utilisées.</p>
                    ";
                break;
            case 'burnout':
                $advices[self::CATEGORY] = "
                    <p>Le burnout est un état de fatigue émotionnelle, physique et mentale causé par un stress excessif et prolongé. Il survient lorsque vous vous sentez débordé(e), émotionnellement vidé(e) et incapable de répondre aux demandes constantes. À mesure que le stress persiste, vous commencez à perdre l'intérêt et la motivation qui vous ont conduit(e) à assumer un certain rôle au départ.</p>
                    <p>Le burnout mental est souvent le résultat d'un engagement excessif dans le travail ou une activité, sans prendre suffisamment de temps pour se reposer et se ressourcer. Il est également associé à un manque de soutien social, à une charge de travail excessive et à des attentes peu claires ou irréalistes au travail ou dans d'autres domaines de la vie.</p>
                    <p>Il est alors important de se fixer des limites claires, vous permettant ainsi de prendre du temps pour vous, afin de vous reposer mentalement et physiquement.</p>
                    <p>Ne laissez pas trainer cet état en espérant que cela passe tout seul. Prenez les devants, et pensez à consulter au moins votre médecin traitant. Envisagez également un accompagnement psychologique auprès d'un psychologue ou d'un psychiatre.</p>
                    ";
                break;
            case 'agoraphobia':
                $advices[self::CATEGORY] = "
                    <p>L'agoraphobie est caractérisée par une peur intense et persistante des espaces ouverts, des foules, ou des situations où s'échapper pourrait être difficile ou où l'aide ne serait pas disponible en cas de panique.</p>
                    <p>Il s'agit d'une situation qui peut devenir handicapante, empêchant de faire des choses normales du quotidien, comme faire ses courses</p>
                    <p>Il est alors important de mettre de bonnes solutions en place afin de surpasser ces craintes. Un accompagnement psychologique est sans doute une des meilleures solutions. La mise en place d'actions cognitives planifiées avec ce thérapeute, comme la thérapie cognitivo-comportementale (TCC), peut vous permettre de retrouver un quotidien loin de toutes ces craintes.</p>
                    ";
                break;
            case 'phobia':
                $advices[self::CATEGORY] = "
                    <p>Les phobies sont un type de trouble anxieux caractérisé par une peur intense et irrationnelle envers un objet, une situation, un lieu, ou une activité spécifique. Cette peur est disproportionnée par rapport au danger réel que représente l'objet ou la situation crainte et peut entraîner un évitement actif ou une détresse sévère dans des situations où l'objet de la peur est présent.</p>
                    <p>Surtout si la phobie devient handicapante au quotidien, le suivi et l'accompagnement psychologique est une bonne solution.</p>
                    <p>Avec cet accompagnement, vous pourrez mettre en place des solutions comme la thérapie cognitivo-comportementale (TCC), qui aide à modifier les pensées et comportements liés à la phobie, et des techniques d'exposition, où l'individu est progressivement exposé à l'objet ou la situation crainte dans un cadre contrôlé.</p>
                    ";
                break;
            case 'toc':
                $advices[self::CATEGORY] = "
                    <p>Les Troubles Obsessionnels Compulsifs (TOC) peuvent devenir très handicapants au quotidien. Ces obsessions et/ou compulsions non contrôlées peuvent prendre une grande part dans le quotidien, et devenir une vraie souffrance.</p>
                    <p>Une des meilleurs approche afin de gérer cela est sans doute l'accompagnement psychologique.</p>
                    <p>Durant cet accompagnement, des méthodes efficaces telles que la thérapie cognitivo-comportementale (TCC), en particulier une forme appelée thérapie d'exposition et de prévention de la réponse (ERP), pourront être mises en place.</p>
                    ";
                break;
            case 'loneliness':
                $advices[self::CATEGORY] = "
                    <p>La solitude est un état émotionnel complexe et souvent douloureux qui survient lorsque les relations sociales d'une personne sont perçues comme insuffisantes ou moins satisfaisantes qu'elles ne le souhaiteraient.</p>
                    <p>Dans un premier temps, parler de cette situation à ses proches, si cela est possible, est une première bonne action. Des associations dans votre entourage peuvent également vous aider.</p>
                    <p>Si votre situation devient handicapante, n'hésitez pas à vous tourner vers un accompagnement par un professionnel de la santé psychique.</p>
                    ";
                break;
            case 'idk':
                $advices[self::CATEGORY] = "
                    <p>Lorsque l'on va mal psychiquement, il n'est pas toujours évident de savoir de quoi cela vient, de quoi on souffre.</p>
                    <p>On a tendance à se tourner vers des recherches internet, afin de se rassurer. Mais ce n'est pas la meilleure idée.</p>
                    <p>La première action à mettre en place est de consulter son médecin traitant afin de lui en parler. Il pourra alors poser un premier diagnostic et vous diriger vers un autre professionnel de santé si cela est nécessaire.</p>
                    ";
                break;
            default:
                $advices[self::CATEGORY] = "
                    <p>Lorsque l'on va mal psychiquement, il n'est pas toujours évident de savoir de quoi cela vient, de quoi on souffre.</p>
                    <p>On a tendance à se tourner vers des recherches internet, afin de se rassurer. Mais ce n'est pas la meilleure idée.</p>
                    <p>La première action à mettre en place est de consulter son médecin traitant afin de lui en parler. Il pourra alors poser un premier diagnostic et vous diriger vers un autre professionnel de santé si cela est nécessaire.</p>
                    ";
        }

        if ($userSolution->isIsDisabling()) {
            $advices['Ca devient handicapant'] = '<p>Un trouble psychique peut être très invalidant.
            À partir de ce moment-là, il est important de se tourner vers un accompagnement psychologique afin de retrouver un quotidien loin de tout cela.
            </p>';
        }

        if ($userSolution->isIsExcitingProduct()) {
            $advices['Votre consommation'] = '<p>Essayez de limiter au maximum les produits excitants, au moins le temps que vous alliez mieux. 
            Ces produits vont favoriser et/ou entretenir votre mal-être.</p>
            <p>Vous pouvez les considérer comme une récompense à consommer lorsque vous avez obtenu une victoire vers votre guérison.</p>';
        }

        if (!$userSolution->isIsDoctorConsulted()) {
            $advices['Votre médecin traitant'] = "<p>Vous devriez consulter votre médecin traitant. Parlez-lui de ce que vous traversez, et de ce que vous ressentez. 
            Si besoin, il vous prescrira des examens complémentaires afin d'écarter toute cause physique à vos symptômes.</p>
            <p>Consulter son médecin traitant est une étape très importante. Et cela apporte toujours une forme de soulagement après.</p>
            ";
        }

        if (!$userSolution->isIsPsyConsulted()) {
            $advices['Le psy'] = "<p>Psychologue et psychiatre sont deux professionnels de santé mentale qui peuvent vous être d'une très grande aide. 
            Ils représentent la meilleure solution d'accompagnement vers la guérison.</p>
            <p>Il est important d'être totalement à l'aise avec le professionnel que vous consultez. Si ce n'est pas le cas, n'hésitez pas à en consulter un autre.</p>
            ";
        }

        if ($userSolution->isIsPsyConsulted() && !$userSolution->isIsPsyAfraid()) {
            $advices['Le psy'] = '<p>Continuer les consultations et discuter des progrès avec le thérapeute. Cet accompagnement est une très bonne chose pour votre mieux être.</p>
            <p>Ça finira par être payant.</p>
            ';
        }

        if (!$userSolution->isIsPsyConsulted() && $userSolution->isIsPsyAfraid()) {
            $advices['Le psy et les aprioris'] = "<p>Ne craignez pas les psychologues et psychiatres.</p>
            <p>Il y a beaucoup d'idées reçues sur la consultation de ces professionnels. Non, ils ne sont pas là pour vous juger, ou vous faire passer pour un fou, ou encore vous assommer de médicaments. Au contraire, en vous écoutant objectivement, ils vous aideront à comprendre ce que vous traversez, et vous donneront des outils pour vous en sortir.</p>
            
            ";
        }

        if (!$userSolution->isIsPsyConsulted() && ('medium' === $userSolution->getCurrentState() || 'high' === $userSolution->getCurrentState())) {
            $advices['Votre état'] = "<p>Vous semblez ressentir votre mal-être très régulièrement dans votre quotidien, ce qui peut devenir difficile à vivre.</p>
            <p>Comme conseillé ci-dessus, un accompagnement psychologique peut être une chose très importante pour vous et votre bien-être.</p>
            <p>Il arrive que le délai pour avoir un rendez-vous avec ce type de spécialiste soit long. N'hésitez pas à demander à votre médecin traitant s'il peut vous aider à obtenir une première consultation plus rapidement.</p>
            ";
        }

        return $advices;
    }
}
