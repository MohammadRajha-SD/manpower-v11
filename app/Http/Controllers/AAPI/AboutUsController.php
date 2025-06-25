<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        return response()->json([
            'success' => true,
            'data' => [
                'about_us' => [
                    'id' => 1,
                    'title' => $lang == 'ar' ? 'من نحن' : 'About us',
                    'content' => $lang == 'ar' ? "<p style='font-family: Almarai; color: black;'>
<b>في H Power</b>، نفخر بكوننا تحت مظلة <b>H POWER FZ - LLC</b>، كيان مرخص ملتزم بتحقيق أعلى معايير الاحترافية والنزاهة.
هذا الانتماء يضمن أن منصتنا تعمل بشفافية ومساءلة، وتوفر جسرًا موثوقًا يربط الفنيين المهرة والشركات الموثوقة مع من يبحثون عن خدمات عالية الجودة في الإمارات.
نسعى معًا لتعزيز ثقافة التعاون والتميز، ودفع الابتكار والتقدم في مجتمعنا الديناميكي.
</p>" : "<p style='font-family: Almarai; color: black;'>
<b>At H Power</b>, we pride ourselves on being under the umbrella of <b>H POWER FZ - LLC</b>, a licensed entity committed to upholding the highest standards of professionalism and integrity.
This affiliation ensures that our platform operates with transparency and accountability, providing a trusted bridge connecting skilled professionals and reputable companies with those seeking top-tier services in the UAE.
Together, we strive to foster a culture of collaboration and excellence, driving innovation and progress in our dynamic society.
</p>",

                    'published' => '1',
                ],
                'our_objectives' => [
                    'id' => 2,
                    'title' => $lang == 'ar' ? 'قيمنا' : 'Objectives',
                    'content' => $lang == 'ar' ? "<div><b>الجودة:</b></div><div><b>- </b>نلتزم بتقديم خدمات ذات جودة عالية، نسعى لتلبية وتجاوز توقعات عملائنا من خلال تقديم أفضل المهارات والخبرات.</div><div><b><br></b></div><div><b>الشفافية:</b></div><div><b>- </b>نؤمن بأهمية الشفافية في علاقاتنا. نعمل على توفير معلومات صافية ودقيقة حول الخدمات والأسعار لضمان تجربة استخدام شفافة وموثوقة.</div><div><b><br></b></div><div><b>التنوع والشمولية:</b></div><div><b>-</b> نقدر التنوع ونعتبره مصدرًا للقوة. نتعاون مع مقدمي خدمات متنوعين ونرحب بأفراد مختلفين لتشكيل مجتمع يعكس تنوع الطموحات والمهارات.</div><div><br></div>" :
                        "<div><b>Ease of communication:</b></div><div><b>-</b> We aim to enable individuals in the United Arab Emirates to easily access high-skilled services for the services they need.</div><div><b><br></b></div><div><b>Building a collaborative community:</b></div><div><b>-</b> We aim to build a community based on cooperation and positive interaction between service providers and users, where they exchange notes and comments.</div><div><br></div><div><b>Commitment to innovation and development:</b></div><div><b>- </b>We always strive to improve and develop our services, and respond quickly to user aspirations and changing market needs.</div>",
                    'published' => '1',
                ],
                'our_values' => [
                    'id' => 3,
                    'title' => $lang == 'ar' ? 'أهدافنا' : 'Our Goals',
                    'content' => $lang == "ar" ? "<div><b style=font-size: 1rem;>سهولة التواصل:</b></div><div><b>-&nbsp; </b>نهدف إلى تمكين الأفراد في دولة الامارات العربية المتحدة من الوصول بسهولة إلى مهارات عالية للخدمات التي يحتاجونها.</div><div><b><br></b></div><div><b>بناء مجتمع تعاوني:</b></div><div><b>-&nbsp;</b> نهدف إلى بناء مجتمع يقوم على التعاون والتفاعل الإيجابي بين مقدمي الخدمات والمستخدمين، حيث يتبادلون الملاحظات والتعليقات.</div><div><b><br></b></div><div><b>التزام بالابتكار والتطوير:</b></div><div><b>-&nbsp; </b>نسعى دائمًا لتحسين وتطوير خدماتنا، ونستجيب بسرعة لتطلعات المستخدمين وتغيرات احتياجات السوق.<br></div>"
                        : "<p><span>Quality:</span></p><p style=font-size: 1rem;><b>-</b> We are committed to providing high-quality services. We strive to meet and exceed our customers’ expectations by providing the best skills and experience.</p><p><br></p><p><span>Transparency:</span></p><p><span>- We believe in the importance of transparency in our relationships. We work to provide clear and accurate information about services and prices to ensure a transparent and reliable user experience.</span></p><p><br></p><p><span>Diversity and Inclusion:</span></p><p><span>- We value diversity and consider it a source of strength. We collaborate with diverse service providers and welcome different individuals to form a community that reflects diversity of ambitions and skills.</span></p>",
                    'published' => '1',
                ],
                'our_vision' => [
                    'id' => 4,
                    'title' => $lang == 'ar' ? 'رؤيتنا' : 'Our Vision',
                    'content' => $lang == 'ar' ? "<div>نسعى لتحفيز المهارات وتسهيل الوصول إلى الخدمات عبر منصة <b>( H Power ). </b>نحن ملزمون بتوفير بيئة آمنة وموثوقة للمستخدمين لتعزيز التواصل والتعاون. حيث يلتقي الباحثين عن خدمات بالمتميزين في كل مجال بكل يسر وسهولة.</div><div><br></div>" :
                        "<p>We seek to stimulate skills and facilitate access to services through <b style=font-size: 1rem;>(H Power) </b><span style=font-size: 1rem;>platform. We are committed to providing a safe and reliable environment for users to enhance communication and collaboration. Where those searching for services meet distinguished people in every field with ease.</span></p>",
                    'published' => '1',
                ],
            ],
            'message' => 'About info fetched successfully',
        ]);
    }
}
