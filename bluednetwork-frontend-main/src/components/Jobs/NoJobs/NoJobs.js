import React from "react";

import "../AvailableJobs/index.css";

import NoJobImage from "../../../assets/noJobIcon.svg";

const NoJobs = () => {
  return (
    <div className="av-jobs_big-wrapper">
      <h2 className="av-h2">Job Openings</h2>
      <div className="container mt-5 av-job_content-wrap">
          {/* Job-content-wrapper */}
          <div className="no-job__content">
            <h3 className="no-job-heading">Oops!</h3>
            <p>
              There are no job vacancies at Blue-D at the moment. Drop your
              email address to get notified when new vacancies are available
            </p>
          </div>

          {/* Subscribe */}

          {/* Job Image */}
          <div className="no-job-Img">
            <img src={NoJobImage} alt="" className="img-fluid"/>
          </div>
      </div>
    </div>
  );
};

export default NoJobs;
