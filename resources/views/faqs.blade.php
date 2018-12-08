@extends('layouts.app', ['skipFoot' => true])

@section('content')
    <div class="container">
        <div class="toggle">
            <div class="toggle-title ">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> What is considered to be personal data?</span>
                </h3>
            </div>
            <div class="toggle-inner">
                <p>"Personal data" means any data that can be used to identify an individual, including:</p>
                <ul>
                    <li>Name</li>
                    <li>Address(es)</li>
                    <li>Email</li>
                    <li>IP address</li>
                    <li>Cookie ID</li>
                    <li>Credit card number</li>
                    <li>Order number</li>
                    <li>Social media account</li>
                </ul>
                <br />
                <p>Please note that “Personal data” does not include any financial information and therefore cannot be linked to an individual, such as:</p>
                <br />
                <ul>
                    <li>How many times a specific product has been sold</li>
                    <li>How much revenue your store has made</li>
                </ul>

                </p>
            </div>
        </div><!-- END OF TOGGLE -->

        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> I'm a Shopify merchant outside of EU. Why does it apply to me?</span>
                </h3>
            </div>

            <div class="toggle-inner">
                <p>The GDPR rules apply to any organization that is offering goods or services to people in the EU irrespective of being
                    based in the EU or outside. All corporations falling under this category will have to comply with the new GDPR rules
                    and should be working on a compliance strategy. </p>
            </div>
        </div><!-- END OF TOGGLE -->

        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> How does "{{env('APP_NAME_FORMATTED')}}" help your Shopify store to achieve GDPR compliance?</span>
                </h3>
            </div>

            <div class="toggle-inner">
                <p>GDPR compliance requires all merchants to provide 8 basic rights to all potential customers.
                    "{{env('APP_NAME_FORMATTED')}}" makes your shopify store compliant with 4 of these rights and there are
                    plans for 3 more modules to be added in the near future.<br /><br />
                    Following is the list of all 8 of these basic rights.
                </p>
                <table>
                    <tr>
                        <th>GDPR Rights</th>
                        <th>{{env('APP_NAME_FORMATTED')}} Compliance</th>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-of-access/" target="_blank">The right of access</a></td>
                        <td>YES</td>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-rectification/" target="_blank">The right to rectification</a></td>
                        <td>YES</td>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-erasure/" target="_blank">The right to erasure</a></td>
                        <td>YES</td>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-data-portability/" target="_blank">The right to data portability</a></td>
                        <td>YES</td>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-be-informed/" target="_blank">The right to be informed (Privacy policy + Consent + Cookie bar)</a></td>
                        <td>Coming Soon</td>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-restrict-processing/" target="_blank">The right to restrict processing (Customer account disable)</a></td>
                        <td>Coming Soon</td>
                    </tr>
                    <tr>
                        <td><a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-object/" target="_blank">The right to object (Newsletter unsubscribe)</a></td>
                        <td>Coming Soon</td>
                    </tr>
                </table>
            </div>
        </div><!-- END OF TOGGLE -->

        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> Why isn't GDPR compliance free?</span>
                </h3>
            </div>

            <div class="toggle-inner">
                <p>All information collected through your store is being secured in an encrypted database which requires
                    charges to be made. This information is made available for you at any time during any event like a GDPR
                    audit which requires you to prove unambiguous, active consent from all your users.</p>
            </div>
        </div><!-- END OF TOGGLE -->

        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> List of EU Countries?</span>
                </h3>
            </div>

            <div class="toggle-inner">
                <p>Following are the countries in the EU region.</p>
                <p> Austria (AT), Belgium (BE), Bulgaria (BG), Croatia (HR), Cyprus (CY), Czechia (CZ), Denmark ('DK),
                    Estonia (EE), Finland (FI), France (FR), Germany (DE), Greece (GR), Hungary (HU), Ireland (IE), Italy (IT),
                    Latvia (LV), Lithuania (LT), Luxembourg (LU), Malta (MT), Netherlands (NL), Poland (PL), Portugal (PT),
                    Romania (RO), Slovakia (SK), Slovenia (SI), Spain (ES), Sweden (SE), United Kingdom (GB)</p>
            </div>
        </div><!-- END OF TOGGLE -->

        <div class="toggle">
            <div class="toggle-title">
                <h3>
                    <span class="title-name"><img src="/images/question.png"> Uninstalling "{{env('APP_NAME_FORMATTED')}}"</span>
                </h3>
            </div>

            <div class="toggle-inner">
                <ul>
                    <li>Once you have uninstalled {{env('APP_NAME_FORMATTED')}} - all information, consent history will be deleted as per the guidelines by Shopify and GDPR rules.</li>
                    <li><b>Clean Uninstall</b> under the action menu on top, is the preferred way to uninstall the app, as it will take care of all the garbage collection and will clean up
                        everything related to the app from your theme as well, and securely remove the app from your shop.</li>
                    <li>Manual cleaning up of code from theme is explained in detail under the <b>Help -> Theme Modification</b> section. Please refere to that if you have any concerns about
                        how we handle theme modification.</li>
                </ul>
            </div>
        </div><!-- END OF TOGGLE -->

    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        if ($(".toggle .toggle-title").hasClass('active')) {
            $(".toggle .toggle-title.active").closest('.toggle').find('.toggle-inner').show();
        }
        $(".toggle .toggle-title").click(function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass("active").closest('.toggle').find('.toggle-inner').slideUp(200);
            }
            else {
                $(this).addClass("active").closest('.toggle').find('.toggle-inner').slideDown(200);
            }
        });
    </script>
    <style>
        html {
            overflow: hidden;
        }
        body {
            overflow-x: hidden;
            overflow-y: scroll;
            box-sizing: content-box;
        }
        .flex-center {
            display: block;
        }
    </style>
@endsection
