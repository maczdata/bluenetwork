import React from "react";
import Ecosystem from "../../components/ecosystem/Ecosystem";
import Footer from "../../components/footer/Footer";
import Header from "../../components/header/Header";
import "./PrivatePolicy.css";

const PrivatePolicy = () => {
  return (
    <>
      <Header />
      <div className='private-policy-wrapper'>
        <div className='private-policy-title'>
          <h2>Privacy Policy</h2>
        </div>
        <div className='private-policy-content p-2'>
          <p>
            This site belongs to and is operated by Blue-D Services (” the
            Company”), having its website as bluedservices.com.ng (“the
            Website”). Blue-D Services provides a variety of digital and
            creative services. We do not overlook your privacy on the internet,
            and will, therefore, not share any personal information of yours
            with third parties. Since we will be gathering different kinds of
            data from our users, it is expedient that you understand our Privacy
            Policy and Terms and Conditions with clarity. In this policy, we
            disclose the types of data we get from you and what they are used
            for. We guarantee that the information you provide on the website
            (bluedservices.com.ng) will only be used for its true and intended
            purpose. In the event that you use the Website, you agree to the
            data processing measures described below.
          </p>

          <h2>Personal Information</h2>

          <p>
            Personal Information Blue-D Services will not collect any personal
            information when you visit the Website, except you provide the
            information to us. It is voluntary to give us your personal
            information, and when you do give this information, you are giving
            Blue-D Services the right to use the provided information for its
            intended purpose. The Company is unable, however, to provide you
            with the Services, if specific information is not provided. There
            are different ways you can give us personal information like sending
            an email to support@bluedservices.com.ng, placing a call through to
            us, submitting information through the Website bluedservices.com.ng,
            sending us a text via sms or WhatsApp. When such information is
            provided, we may employ the data to deliver your desired information
            or services (all together referred to as (“the Services”)). We may
            also respond to your feedback or messages with the personal
            information you provide us. The personal information or data we may
            receive varies and is dependent mainly on what you do when you visit
            the Website.
          </p>

          <p>
            We may also use personal information to respond to any of your
            message(s) or feedback(s). The information we may receive from you
            varies based on what you do when visiting the Website.
          </p>

          <h2>
            <span>i. </span>Collection of Personal Information
          </h2>

          <p>
            When using the Website, you may provide necessary information such
            as your name, surname, mobile phone number, email address and wallet
            address. However, the information you may provide is not limited to
            the above and will let us send you information, process your desired
            Services and send updates. Signing up with Blue-D Services or
            purchasing the Services from Blue-D Services may also require that
            we collect your credit card, and billing information, among other
            things. Blue-D Services encourages that you review the Privacy
            Statement of third party merchants from the Company, to understand
            the information collection and use procedure. Blue-D Services is not
            responsible for privacy statements of Third Party Merchants that are
            outside the Company.
          </p>

          <h2>
            <span>ii.</span> Automatically Collected Information
          </h2>

          <p>
            We may store some information temporarily about your visit to the
            Website. This information is used for security purposes and site
            management, helping us design the Website to suit your needs. In the
            event of a known virus threat or security, we may collect
            information automatically about the web content that you view. The
            report includes your IP address (Internet Protocol Address: a unique
            device number for each device connected to the internet), the
            operating system, type of browser used to access our Website, the
            URLs (Universal Resource Locators) you visited (page addresses) or
            from which you visited the Website (If you visited the Website from
            a different website), the time and date you access our Website.
          </p>

          <h2>
            <span>iii.</span> Cookies
          </h2>

          <p>
            We will transfer a small data from our Website to your device. This
            is done to let our Website remember information quickly about your
            previous session when next you get connected. This file transferred
            to your device is known as a ‘cookie’. Be assured that the
            information will not be shared with any other website, save our
            Website, which was the source of information. A different website
            will not be able to request it. The two types of cookies are the
            Session and Persistent Cookies. The Session Cookies only last until
            your web browser is closed, as long as your browser is open, it
            stays. The Persistent Cookies get stored in your hard drive for when
            you’re revisiting the Website in the future. This way, the Website
            can differentiate new users from repeat users.
          </p>

          <h2>
            <span>iv.</span> Personal Information Use
          </h2>

          <p>
            The personal information Blue-D Services gets from you is solely to
            deliver your desired Services and efficiently operate the Website.
            The Company will not disclose your personal information to third
            parties. However, the Company may reach out to you to inform you of
            an offering from, but not limited to third parties, that we think
            you may find interesting. In that case, we may then transfer
            personal information to the third party. The third parties are
            restricted from using your personal information for anything other
            than delivering the Services to the Company.
          </p>

          <p>
            The Company will also monitor the Website, tracking the pages our
            users visit. This activity is observed to be aware of the most
            popular Services of the Company among users. The Company, then, uses
            this information to customise content and advertise to customers
            who, from our studies, are interested in a subject area.
          </p>

          <h2>
            <span>v. </span>Personal Information Disclosure
          </h2>

          <p>
            The personal information that you provide may be disclosed to our
            staff, professionals, agents, employees, suppliers, subcontractors
            or any other parties we deem fit. This is done to enable the
            execution and delivery of your desired Services, and for reasons
            described in our privacy policy. When third parties receive your
            personal information, all the terms and conditions specified still
            hold, to protect your personal information.{" "}
          </p>
        </div>
      </div>
      {/* Empowered Section */}
      <Ecosystem />

      <Footer />
    </>
  );
};

export default PrivatePolicy;
