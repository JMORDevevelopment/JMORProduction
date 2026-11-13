<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LINK = 'our-dei-diversiety-equity-inclusion-non-discrimination-policy';

    private const NAME = 'Our DEI (Diversity, Equity & Inclusion) Non-Discrimination Policy';

    private const MENU_TITLE = 'Our DEI (Non-Discrimination Policy)';

    private const IMAGE = 'assets/images/pages/jmor-dei.jpg';

    public function up(): void
    {
        $page = DB::table('pages')->where('link', self::LINK)->first();

        if ($page) {
            $pageId = $page->id;
            $updates = [];

            if (trim((string) $page->description) === '') {
                $updates['description'] = self::content();
            }

            if (trim((string) $page->image) === '') {
                $updates['image'] = self::IMAGE;
            }

            if (trim((string) $page->name) === '') {
                $updates['name'] = self::NAME;
            }

            if ($updates !== []) {
                DB::table('pages')->where('id', $pageId)->update($updates);
            }
        } else {
            $pageId = DB::table('pages')->insertGetId([
                'link' => self::LINK,
                'name' => self::NAME,
                'priority' => 0,
                'slider_status' => 0,
                'menu_location' => 2,
                'description' => self::content(),
                'image' => self::IMAGE,
                'form_id' => 0,
                'meta_title' => self::NAME,
                'meta_keywords' => 'diversity, equity, inclusion, DEI, non-discrimination policy, JMOR',
                'meta_description' => 'The JMOR Connection, Inc. is committed to a diverse, inclusive, and respectful environment for employees, clients, and partners.',
                'menu_status' => 1,
            ]);
        }

        $menuExists = DB::table('menu')->where('url', self::LINK)->exists();

        if (! $menuExists) {
            DB::table('menu')->insert([
                'parent_id' => 2,
                'title' => self::MENU_TITLE,
                'url' => self::LINK,
                'position' => 5,
                'group_id' => 1,
                'menu_type' => 'Pages',
                'page_id' => $pageId,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu')
            ->where('url', self::LINK)
            ->where('title', self::MENU_TITLE)
            ->delete();

        DB::table('pages')
            ->where('link', self::LINK)
            ->where('name', self::NAME)
            ->where('description', self::content())
            ->delete();
    }

    private static function content(): string
    {
        return <<<'HTML'
<p><strong>The JMOR Connection, Inc. Non-Discrimination Policy</strong></p>
<p><strong>Effective Date</strong>: Jan 1, 2025</p>
<p><strong>Introduction: Commitment to Diversity and Inclusion</strong></p>
<p>At <strong>The JMOR Connection, Inc.</strong>, we are committed to providing a workplace and business environment that is free from all forms of discrimination. We believe in treating all individuals with dignity and respect and are dedicated to fostering an inclusive environment where everyone is valued equally. This policy outlines the company&rsquo;s stance on discrimination, detailing our approach to ensure fairness and equality for everyone, including employees, contractors, prospective clients, clients, vendors, and sales representatives.</p>
<p>Discrimination, in any form, undermines our core values of integrity, professionalism, and mutual respect. This policy serves as a guideline for how we conduct ourselves within our organization and how we interact with external partners, setting clear expectations of behavior for all parties involved.</p>
<p><strong>Core Principles of The JMOR Connection, Inc.</strong></p>
<p>We believe that a diverse, equitable, and inclusive environment strengthens our business, enhances creativity and innovation, and fosters a culture where individuals can thrive. Diversity is not just a set of ideals; it is an actionable commitment that guides our decisions and shapes our relationships with clients, employees, and external partners. Our core principles are founded on respect for all people, regardless of background, identity, or circumstance.</p>
<ol>
<li><strong>Diversity</strong> is about recognizing and valuing the differences among individuals, including differences in race, ethnicity, gender, sexual orientation, religion, and abilities.</li>
<li><strong>Equity</strong> is about ensuring that all individuals have equal access to opportunities, resources, and decision-making processes within the company and in our dealings with others.</li>
<li><strong>Inclusion</strong> is about creating an environment where all individuals, regardless of their backgrounds or identities, feel welcomed, respected, and able to fully participate in the workplace and business relationships.</li>
</ol>
<p><strong>Purpose of the Policy</strong></p>
<p>The purpose of this <strong>Non-Discrimination Policy</strong> is to demonstrate our commitment to maintaining a workplace and business environment where discrimination is not tolerated. It defines our zero-tolerance approach to discrimination and harassment and sets the expectations for everyone&mdash;whether employees, contractors, prospective clients, clients, vendors, or sales representatives&mdash;on how to contribute to an environment of respect and fairness.</p>
<p>This policy reinforces our ongoing efforts to create an equitable and inclusive space where diversity is embraced, discrimination is actively opposed, and everyone is given the opportunity to succeed and thrive.</p>
<p><strong>Scope of the Policy</strong></p>
<p>This policy applies to all individuals who interact with <strong>The JMOR Connection, Inc.</strong> in the course of our operations, including:</p>
<ul>
<li><strong>Employees</strong>: Full-time, part-time, temporary, and seasonal staff who work directly with the company.</li>
<li><strong>Contractors</strong>: Independent contractors and consultants who engage in projects or provide services to <strong>The JMOR Connection, Inc.</strong>.</li>
<li><strong>Prospective Clients</strong>: Individuals or businesses seeking to work with <strong>The JMOR Connection, Inc.</strong> on a potential basis.</li>
<li><strong>Clients</strong>: Current clients or customers who engage our services.</li>
<li><strong>Vendors</strong>: Third-party suppliers or companies that provide goods or services to the organization.</li>
<li><strong>Sales Representatives</strong>: Individuals or companies responsible for promoting and selling their company&rsquo;s services or products.</li>
</ul>
<p>All individuals within the organization and in partnership with the company must adhere to this policy to ensure that discriminatory behavior is eradicated and that a culture of respect is established and maintained.</p>
<p><strong>Defining Discrimination and Harassment</strong></p>
<p><strong>Discrimination</strong> refers to the unjust or prejudicial treatment of individuals based on their characteristics, such as race, ethnicity, gender, age, disability, or other personal attributes unrelated to their qualifications, skills, or job performance. It may manifest in the form of unequal treatment in hiring practices, promotions, assignments, compensation, or other aspects of professional interaction.</p>
<p><strong>Harassment</strong> is a form of discrimination involving unwanted and inappropriate behavior, comments, or actions that create a hostile, intimidating, or offensive environment. Harassment can take many forms, including verbal, physical, and emotional abuse. It can occur in person, via email, or on social media platforms. Harassment includes any action or comment that negatively affects another individual based on their personal characteristics or identities.</p>
<p>We do not tolerate either discrimination or harassment in any form. These behaviors undermine the mutual respect and dignity we strive to create in all our relationships.</p>
<p><strong>Types of Discrimination Not Tolerated</strong></p>
<p>Discrimination can take many different forms, all of which are unacceptable. Below is a comprehensive list of specific types of discrimination that <strong>The JMOR Connection, Inc.</strong> prohibits in the workplace and business dealings:</p>
<ol>
<li>
<p><strong>Racial Discrimination</strong>: Any form of bias or prejudice based on race or ethnicity is prohibited. This includes stereotyping, racial slurs, and other actions that make individuals feel less than or unwelcome based on their racial or ethnic background.</p>
</li>
<li>
<p><strong>Gender and Gender Identity Discrimination</strong>: Discrimination against individuals based on gender, gender identity, or gender expression is strictly forbidden. <strong>The JMOR Connection, Inc.</strong> supports the right of every person to express their gender identity openly and without fear of exclusion or harassment.</p>
</li>
<li>
<p><strong>Sexual Orientation Discrimination</strong>: Discrimination against individuals based on their sexual orientation, including those who identify as lesbian, gay, bisexual, queer, or any other non-heterosexual identity, is unacceptable.</p>
</li>
<li>
<p><strong>Age Discrimination</strong>: Individuals should not be judged or excluded from opportunities based on their age. We reject both age-based bias against younger individuals and older employees.</p>
</li>
<li>
<p><strong>Disability Discrimination</strong>: We are committed to making reasonable accommodations for individuals with disabilities and providing equal opportunities for all employees and contractors. Discriminating against individuals with physical or mental disabilities is prohibited.</p>
</li>
<li>
<p><strong>Religious Discrimination</strong>: All employees, contractors, and external partners have the right to practice their religion freely and openly. Discrimination or harassment based on religious beliefs, practices, or attire is strictly prohibited.</p>
</li>
<li>
<p><strong>National Origin and Ethnicity Discrimination</strong>: Discrimination based on one&rsquo;s place of birth, national origin, or ethnic background is forbidden. We celebrate the diverse cultures that make up our community and our workforce.</p>
</li>
<li>
<p><strong>Appearance and Body Type Discrimination</strong>: No individual should be judged or treated unfairly based on their physical appearance, body size, or any other irrelevant factor related to their looks.</p>
</li>
</ol>
<p><strong>Commitment to Equality and Opportunity</strong></p>
<p>At <strong>The JMOR Connection, Inc.</strong>, we are proud to operate under an equal opportunity framework. This ensures that all employees, contractors, and business partners are given the same opportunities, regardless of their background, gender, race, or other characteristics. We are committed to ensuring that our hiring processes, employee development programs, and business relationships are guided by merit, capabilities, and the potential for success rather than irrelevant factors such as appearance, age, or personal identity.</p>
<p>We actively seek to identify and remove any barriers that may contribute to unequal opportunities. This includes reviewing our policies and practices regularly, assessing the fairness of our compensation structures, and ensuring our recruitment methods are inclusive.</p>
<p><strong>Accountability for Discrimination-Free Practices</strong></p>
<p><strong>The JMOR Connection, Inc.</strong> holds all employees, contractors, prospective clients, clients, vendors, and sales representatives accountable for upholding this Non-Discrimination Policy. We expect that everyone involved with our company will contribute to maintaining a respectful and inclusive environment, and will avoid any behaviors that may contribute to exclusion, prejudice, or inequality.</p>
<p>Failure to adhere to the standards set out in this policy may result in serious consequences, including but not limited to the termination of employment, suspension of contracts, or cessation of business relationships. We recognize that maintaining a non-discriminatory culture requires both individual responsibility and collective commitment.</p>
<p><strong>Creating an Inclusive Work Environment</strong></p>
<p>An inclusive environment is one where everyone feels valued, respected, and empowered to contribute to the success of the organization. We believe that an inclusive environment is a cornerstone of a healthy, productive workplace. As such, <strong>The JMOR Connection, Inc.</strong> is committed to:</p>
<ul>
<li><strong>Training</strong>: Providing regular diversity and inclusion training for employees, contractors, and leadership to ensure that everyone understands and embodies our core values of respect, fairness, and equality.</li>
<li><strong>Awareness</strong>: Encouraging awareness and self-reflection on issues related to discrimination, privilege, and bias, so that all individuals understand their role in shaping a welcoming, inclusive culture.</li>
<li><strong>Support</strong>: Offering support systems such as mentorship programs, employee resource groups, and counseling services to create a safe space for individuals to connect, share experiences, and grow professionally.</li>
</ul>
<p>We recognize that inclusion is a continuous process and that progress is achieved when we take consistent, measurable actions to make our workplace and business relationships more inclusive.</p>
<p><strong>Ongoing Commitment to Diversity and Inclusion</strong></p>
<p>At <strong>The JMOR Connection, Inc.</strong>, we view diversity and inclusion as an ongoing commitment that requires attention, learning, and growth. We are constantly working to improve our efforts in creating an inclusive workplace and fostering relationships with clients, contractors, and partners who share our values.</p>
<p>This policy will be reviewed and updated periodically to reflect our evolving understanding of diversity and to meet the needs of our organization and the communities in which we operate. As part of this commitment, we welcome feedback and suggestions for improvement from employees, clients, and external partners.</p>
HTML;
    }
};
