import React from "react";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import Footer from "../../components/footer/Footer";
import Header from "../../components/header/Header";
import "./TermsAndCondition.css";

const TermsAndCondition = () => {
  return (
    <>
      <Header />
      <div className='private-policy-wrapper'>
        <div className='private-policy-title px-2'>
          <h2>Terms & Conditions</h2>
        </div>
        <div className='private-policy-content px-2'>
          <p>
            Thank you for choosing Blue-D Services. The terms and conditions you
            will find below are the governing conditions for the use of
            bluedservices.com.ng. This includes all of the Content, products, or
            services you receive through or on the Website. Blue-D Services is
            the Company to which the Website belongs. Your use of the Website is
            determined by your compliance and acceptance of all the terms and
            conditions contained herein, not excluding the other governing rules
            like the Privacy Policy and future procedures that may be
            occasionally published on this Website by Blue-D Services (this is
            collectively referred to as the “Agreement”). You must go through
            the Agreement carefully before using the Website. In the event that
            you go ahead to use the Website, you, therefore, agree that you will
            become bound by the terms and conditions provided. If, after you
            have gone through the terms and conditions, you do not agree, then
            you may not use the Services on the Website. If the terms of Service
            is considered to be an offer by Blue-D Services, then these terms
            are the limit of the acceptance. Only individuals above thirteen
            years of age are allowed to have access to this Website.
          </p>

          <h2>Your Bluedservices.com.ng Account</h2>

          <p>
            If, or, when you create an account on the Website, you are entirely
            responsible for protecting your account, and shoulder the
            responsibility for every activity that goes on with your account. If
            you notice an unauthorized usage of your account or any other kind
            of security breach, report to Blue-D Services immediately. The
            Company will not take responsibility for any omission or act by you,
            and this includes any damages that occur as a consequence of said
            omission or act.
          </p>

          <p>
            We may also use personal information to respond to any of your
            message(s) or feedback(s). The information we may receive from you
            varies based on what you do when visiting the Website.
          </p>

          <h2>Contributor Responsibility</h2>

          <p>
            If you upload any material to the Website or make material available
            through the Website, be it a graphic, text, software, or audio
            material, you shoulder the responsibility for that Content,
            including whatever harm may result from the Content. In your making
            of Content available, you are stating that:
          </p>

          <p>
            THE USE, COPYING, OR DOWNLOADING OF THE CONTENT WILL, IN NO WAY,
            INFRINGE THE PROPRIETARY RIGHTS, WHICH INCLUDES (BUT NOT IN ANY WAY
            LIMITED TO) THE COPYRIGHT, TRADE SECRET RIGHTS, OR TRADEMARK, OF ANY
            THIRD PARTY. IF THE INTELLECTUAL PROPERTY YOU MAKE AVAILABLE CAN BE
            CLAIMED BY YOUR EMPLOYER, THEN YOU HAVE EITHER (a) SECURED A WAIVER
            FROM YOUR EMPLOYER AS TO ALL RIGHTS TO OR IN THE CONTENT, OR (b)
            SOUGHT AND RECEIVED PERMISSION FROM SAID EMPLOYER TO MAKE THE
            CONTENT AVAILABLE; YOU HAVE GONE THROUGH THE LICENSE OF ANY THIRD
            PARTY CONNECTIONS TO THE CONTENT AND COMPLIED, AND HAVE ALSO MADE
            ALL EFFORTS TO PASS TO THE USERS OF AVAILABE CONTENT ANY NECESSARY
            TERMS; YOU HAVE CONFIRMED THAT THERE ARE NO VIRUSES, OR ANYTHING
            HARMFUL CONTAINED IN THE CONTENT; THE CONTENT IS NOT A SPAM OR AN
            ATTEMPT TO DRIVE TRAFFIC TO BOOST SEARCH ENGINE RANKINGS OR DRIVE
            TRAFFIC TO THIRD PARTY SITES, NOT RANDOMLY OR MACHINE-GENERATED, NOT
            DESIGNED TO DRIVE USERS TO UNLAWFUL ACTS OR MISLEAD AS TO THE
            MATERIAL SOURCE; THE CONTENT IS NOT MISCHIEVOUSLY NAMED TO MISLEAD
            USERS INTO THINKING YOUR ARE A DIFFERENT COMPANY OR PERSON; THE
            CONTENT DOES NOT CONTAIN PORNOGRAPHIC MATERIAL, THREATS OR VIOLENCE,
            AND DOES NOT VIOLATE THE PUBLICITY OR PRIVACY RIGHTS OF A THIRD
            PARTY; WHETHER REQUESTED BY BLUE-D SERVICES OR NOT, YOU HAVE DONE A
            THOROUGH JOB TO PROPERLY DESCRIBE OR CATEGORIZE THE KIND, EFFECTS OR
            NATURE OF THE MATERIALS, IN THE CASE THAT IT CONTAINS A COMPUTER
            CODE; THE CONTENT IS NOT BEING ADVERTISED THROUGH UNWANTED
            E-MESSAGES LIKE BLOGS, WEBSITES, EMAIL LISTS, SPAM LINKS AND OTHER
            PROMITIONAL METHODS THAT ARE UNSOLICITED.
          </p>

          <h2>On Renewal and Optional Payments</h2>

          <p>
            At the moment, no renewable services exist on the platform. Also,
            there are no optional paid upgrades or services on the Website yet.
          </p>

          <h2>Identity Verification</h2>

          <p>
            Blue-D Services takes the KYC (Know Your Customer) process seriously
            and maintains a high standard on this note. The standard maintained
            reflects how committed we are to combating fraudulent practices. As
            part of our standards, specific personal details and documents are
            required from you. The extent and nature of your Identity
            verification largely depends on your kind of transaction, the nature
            of Service you are in need of, and where you reside in. Using the
            services mean that you Agree and will remain compliant with the
            procedures always. Blue-D Services reserves the right to suspend or
            entirely terminate your account if you’re suspected or have been
            found to have provided false or no information at all. You accept
            that during our Identity Verification procedures, there may be
            delays in accessing your account on the Website.
          </p>

          <p>
            Blue-D Services will need to retain specific information obtained.
            This is a part of the Identification Verification process and still
            applies even if you opt-out of the use of bluedservices.com.ng.
            Blue-D Services reserves the right to keep such data and
            documentation you provide for a required period, and you accept that
            the details you provide to us may be retained by us, including after
            the closure of your account on the Website.
          </p>

          <h2>Service Availability</h2>

          <p>
            Blue-D Services will do all to continue to provide the Services to
            you. However, we do not give any warranty as regards the
            availability of the Website or your account. We do not, at any
            point, guarantee uninterrupted access to the Website or your account
            and make no representation that the Services, account or Website
            will always be available, or that there will be zero errors, delays,
            omissions, failures or transmitted information loss.
          </p>
        </div>
      </div>
      {/* Empowered Section */}
      <Ecosystem />

      <Footer />
    </>
  );
};

export default TermsAndCondition;
