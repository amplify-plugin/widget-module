{{-- <div class="card mb-3">
    <div class="p-4 text-center text-white text-lg bg-dark rounded-top"><span class="text-uppercase">REQUEST RHS PRINT
            CATALOG</span></div>
    <div class="card-body">
        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-center padding-top-1x padding-bottom-1x">
            <div class="model_serial_number w-100">
                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('model-serial-number-research.store') }}">
                    @csrf

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="contact_name" placeholder="Contact Name"
                                value="{{ old('contact_name') }}">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="job_title" placeholder="Job Title"
                                value="{{ old('job_title') }}">
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="company_name" placeholder="Company Name"
                                value="{{ old('company_name') }}">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="address" placeholder="Address"
                                value="{{ old('address') }}">
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="city" placeholder="City"
                                value="{{ old('city') }}">
                            @error('city')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="zip_code" placeholder="Your ZIP Code"
                                value="{{ old('zip_code') }}">
                            @error('zip_code')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="state" placeholder="State"
                                value="{{ old('state') }}">
                            @error('state')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="phone_number" placeholder="Phone Number"
                                value="{{ old('phone_number') }}">
                            @error('phone_number')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="email_address"
                                placeholder="Your Email Address" value="{{ old('email_address') }}">
                            @error('email_address')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="g-recaptcha mt-4" data-sitekey={{config('services.recaptcha.key')}}></div>
                    </div>

                    <div class="d-flex  justify-content-center ">
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@php
@pushonce('plugin-script')
	<script src="{{ asset('https://www.google.com/recaptcha/api.js') }}"></script>
@endpushonce
@endphp --}}

<style>
    .sidebar {
        height: 100vh;
        /* position: fixed; */
        width: 320%;
        /* background-color: #f8f9fa; */
        overflow-y: auto;
    }

    .list-group .nav-link {
        text-decoration: none;
    }

    .address-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
        padding: 20px;
        background-color: #fff;
    }

    .address-card h5 {
        margin-bottom: 15px;
    }

    .address-card p {
        margin-bottom: 10px;
    }

    .address-card .btn {
        margin-top: 10px;
    }
    @media only screen and (max-width: 600px) {
        .sidebar {
            display: none;
  }
}
</style>


<div class="d-flex flex-row justify-content-center">
    <nav id="sidebar" class="sidebar py-4">
        <div class="list-group">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a href="#address" class="nav-link" href="#">Our role in your privacy <span
                            class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a href="#website_visitors" class="nav-link" href="#">Website Visitors</a>
                </li>
                <li class="nav-item">
                    <a href="#personally-identifying" class="nav-link" href="#">Gathering of Personally-Identifying Information</a>
                </li>
                <li class="nav-item">
                    <a href="#security" class="nav-link" href="#">Security</a>
                </li>

                <li class="nav-item">
                    <a href="#advertisements" class="nav-link" href="#">Advertisements</a>
                </li>
                <li class="nav-item">
                    <a href="#protection" class="nav-link" href="#">Protection of Certain Personally-Identifying Information</a>
                </li>

                <li class="nav-item">
                    <a href="#cookies" class="nav-link" href="#">Cookies</a>
                </li>

                <li class="nav-item">
                    <a href="#ecommerce" class="nav-link" href="#">E-commerce</a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="content mx-3  border border-4 border-secondary rounded p-4 my-3">
        <section id="address" class="text-center">
            <h2>Welcome to our Privacy Policy</h2>
            <p>Your privacy is critically important to us.</p>
            <div class="address-card">
                <span class="card-title">www.rhsparts.com</span>
                <p class="card-text"><strong>Address:</strong> 632 Foresight Circle Grand Junction</p>
                <p class="card-text"><strong>Phone:</strong> 81505 - COLORADO , United States</p>
                <p class="card-text"><strong>Email:</strong> 1 800 716-7788</p>
            </div>
        </section>
        <p class="mt-4">
            It is Www.rhsparts.com's policy to respect your privacy regarding any information we may collect while
            operating our website. This Privacy Policy applies to https://www.rhsparts.com (hereinafter, "us", "we", or
            "https://www.rhsparts.com"). We respect your privacy and are committed to protecting personally identifiable
            information you may provide us through the Website. We have adopted this privacy policy ("Privacy Policy")
            to explain what information may be collected on our Website, how we use this information, and under what
            circumstances we may disclose the information to third parties. This Privacy Policy applies only to
            information we collect through the Website and does not apply to our collection of information from other
            sources.
            <br /><br />
            This Privacy Policy, together with the Terms and conditions posted on our Website, set forth the general
            rules and policies governing your use of our Website. Depending on your activities when visiting our
            Website, you may be required to agree to additional terms and conditions.
        </p>
        <section id="website_visitors">
            <h2>Website Visitors</h2>
            <p>Like most website operators, Www.rhsparts.com collects non-personally-identifying information of the sort
                that web browsers and servers typically make available, such as the browser type, language preference,
                referring site, and the date and time of each visitor request. Www.rhsparts.com's purpose in collecting
                non-personally identifying information is to better understand how Www.rhsparts.com's visitors use its
                website. From time to time, Www.rhsparts.com may release non-personally-identifying information in the
                aggregate, e.g., by publishing a report on trends in the usage of its website.
                <br /><br />

                Www.rhsparts.com also collects potentially personally-identifying information like Internet Protocol
                (IP) addresses for logged in users and for users leaving comments on https://www.rhsparts.com blog
                posts. Www.rhsparts.com only discloses logged in user and commenter IP addresses under the same
                circumstances that it uses and discloses personally-identifying information as described below.
            </p>
        </section>
        <section id="personally-identifying">
            <h2>Gathering of Personally-Identifying Information</h2>
            <p>Certain visitors to Www.rhsparts.com's websites choose to interact with Www.rhsparts.com in ways that
                require Www.rhsparts.com to gather personally-identifying information. The amount and type of
                information that Www.rhsparts.com gathers depends on the nature of the interaction. For example, we ask
                visitors who sign up for a blog at https://www.rhsparts.com to provide a username and email address.</p>
        </section>
        <section id="security">
            <h2>Security</h2>
            <p>The security of your Personal Information is important to us, but remember that no method of transmission
                over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially
                acceptable means to protect your Personal Information, we cannot guarantee its absolute security.</p>
        </section>

        <section id="advertisements">
            <h2>Advertisements</h2>
            <p>Ads appearing on our website may be delivered to users by advertising partners, who may set cookies.
                These cookies allow the ad server to recognize your computer each time they send you an online
                advertisement to compile information about you or others who use your computer. This information allows
                ad networks to, among other things, deliver targeted advertisements that they believe will be of most
                interest to you. This Privacy Policy covers the use of cookies by Www.rhsparts.com and does not cover
                the use of cookies by any advertisers.</p>
        </section>

        <section id="Cookies">
            <h2>Cookies</h2>
            <p>To enrich and perfect your online experience, Www.rhsparts.com uses "Cookies", similar technologies and services provided by others to display personalized content, appropriate advertising and store your preferences on your computer.
                <br/><br/>
                A cookie is a string of information that a website stores on a visitor's computer, and that the visitor's browser provides to the website each time the visitor returns. Www.rhsparts.com uses cookies to help Www.rhsparts.com identify and track visitors, their usage of https://www.rhsparts.com, and their website access preferences. Www.rhsparts.com visitors who do not wish to have cookies placed on their computers should set their browsers to refuse cookies before using Www.rhsparts.com's websites, with the drawback that certain features of Www.rhsparts.com's websites may not function properly without the aid of cookies.

                By continuing to navigate our website without changing your cookie settings, you hereby acknowledge and agree to Www.rhsparts.com's use of cookies.</p>
        </section>

        <section id="protection">
            <h2>Protection of Certain Personally-Identifying Information</h2>
            <p>Www.rhsparts.com discloses potentially personally-identifying and personally-identifying information only
                to those of its employees, contractors and affiliated organizations that (i) need to know that
                information in order to process it on Www.rhsparts.com's behalf or to provide services available at
                Www.rhsparts.com's website, and (ii) that have agreed not to disclose it to others. Some of those
                employees, contractors and affiliated organizations may be located outside of your home country; by
                using Www.rhsparts.com's website, you consent to the transfer of such information to them.
                Www.rhsparts.com will not rent or sell potentially personally-identifying and personally-identifying
                information to anyone. Other than to its employees, contractors and affiliated organizations, as
                described above, Www.rhsparts.com discloses potentially personally-identifying and
                personally-identifying information only in response to a subpoena, court order or other governmental
                request, or when Www.rhsparts.com believes in good faith that disclosure is reasonably necessary to
                protect the property or rights of Www.rhsparts.com, third parties or the public at large.
                <br /><br />
                If you are a registered user of https://www.rhsparts.com and have supplied your email address,
                Www.rhsparts.com may occasionally send you an email to tell you about new features, solicit your
                feedback, or just keep you up to date with what's going on with Www.rhsparts.com and our products. We
                primarily use our blog to communicate this type of information, so we expect to keep this type of email
                to a minimum. If you send us a request (for example via a support email or via one of our feedback
                mechanisms), we reserve the right to publish it in order to help us clarify or respond to your request
                or to help us support other users. Www.rhsparts.com takes all measures reasonably necessary to protect
                against the unauthorized access, use, alteration or destruction of potentially personally-identifying
                and personally-identifying information.
            </p>
        </section>

        <section id="cookies">
            <h2>Cookies</h2>
            <p>To enrich and perfect your online experience, Www.rhsparts.com uses "Cookies", similar technologies and services provided by others to display personalized content, appropriate advertising and store your preferences on your computer.

                A cookie is a string of information that a website stores on a visitor's computer, and that the visitor's browser provides to the website each time the visitor returns. Www.rhsparts.com uses cookies to help Www.rhsparts.com identify and track visitors, their usage of https://www.rhsparts.com, and their website access preferences. Www.rhsparts.com visitors who do not wish to have cookies placed on their computers should set their browsers to refuse cookies before using Www.rhsparts.com's websites, with the drawback that certain features of Www.rhsparts.com's websites may not function properly without the aid of cookies.
                <br/><br/>
                By continuing to navigate our website without changing your cookie settings, you hereby acknowledge and agree to Www.rhsparts.com's use of cookies..
            </p>
        </section>

        <section id="ecommerce">
            <h2>E-commerce</h2>
            <p>Those who engage in transactions with Www.rhsparts.com . by purchasing Www.rhsparts.com's services or products, are asked to provide additional information, including as necessary the personal and financial information required to process those transactions. In each case, Www.rhsparts.com collects such information only insofar as is necessary or appropriate to fulfill the purpose of the visitor's interaction with Www.rhsparts.com. Www.rhsparts.com does not disclose personally-identifying information other than as described below. And visitors can always refuse to supply personally-identifying information, with the caveat that it may prevent them from engaging in certain website-related activities.
            </p>
        </section>
    </div>
</div>
