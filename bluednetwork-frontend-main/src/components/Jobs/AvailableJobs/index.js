import React, { useState } from "react";
import { Link } from "react-router-dom";
import ReadMoreReact from "read-more-react";
// import useModal from "use-react-modal";
import Modal from "react-modal";

//css
import "./index.css";

//Imgs
import JobImageSrc from "../../../assets/JobImage.png";
import closeModalIcon from "../../../assets/closeModalIcon.svg";
import JobApplication from "../../form/JobApplication";

const customStyles = {
  content: {
    position: "fixed",
    height: "auto",
    overflowY: "auto",
    padding: ".4em inherit",
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "-39%",
    marginRight: "-50%",
    transform: "translate(-50%, -50%)",
  },
};

const AvailableJobs = ({ jobsData }) => {
  const [modalIsOpen, setIsOpen] = useState(false);

  function openModal() {
    setIsOpen(true);
  }

  function closeModal() {
    setIsOpen(false);
  }

  return (
    <div className="av-jobs_big-wrapper">
      <h2 className="av-h2">Job Openings</h2>
      <div className="container mt-5 av-job_cards-wrap">
        {jobsData.map((job, id) => (
          <div key={id} className="av-job-card">
            {/* Job Image */}
            <div className="av-job-Img">
              <img src={JobImageSrc} alt="" />
            </div>

            {/* Job-content-wrapper */}
            <div className="av-job-content">
              {/* Job-title */}
              <h3 className="av-h3">{job.title}</h3>

              {/* Job-description */}
              <div className="av-description">
                <ReadMoreReact
                  text={job.description}
                  min={60}
                  ideal={100}
                  max={300}
                  readMoreText={
                    <Link to="#" className="link-color">
                      Read more &gt;
                    </Link>
                  }
                />
              </div>

              {/* Job-apply-btn */}
              <div className="">
                <button
                  onClick={() => openModal()}
                  className="av-job-apply__btn"
                >
                  Apply
                </button>

                <Modal
                  isOpen={modalIsOpen}
                  onRequestClose={closeModal}
                  style={customStyles}
                  overlayClassName="modal-overlay"
                  contentLabel="Example Modal"
                  ariaHideApp={false}
                >
                  <ModalComponent presentJob={job} closeModal={closeModal} />
                </Modal>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default AvailableJobs;

const ModalComponent = ({ presentJob, closeModal }) => {
  const [modalIsOpen, setIsOpen] = useState(false);

  function openModal() {
    setIsOpen(true);
  }

  function closeModal2() {
    setIsOpen(false);
  }
  return (
    <div className="modal-component__wrap">
      {/* Job Title */}
      <div className="modal-title">
        <h3>{presentJob.title}</h3>
        <span onClick={closeModal} style={{ cursor: "pointer" }}>
          <img src={closeModalIcon} alt="" />
        </span>
      </div>

      {/* Job Role */}
      <div className="modal-div">
        <h5>Your Role</h5>
        <p>
          <ReadMoreReact
            text={presentJob.role}
            min={60}
            ideal={100}
            max={300}
            readMoreText={
              <Link to="#" className="link-color">
                Read more &gt;
              </Link>
            }
          />
        </p>
      </div>

      {/* Job Requirements */}
      <div className="modal-div">
        <h5>Requirements</h5>
        <p>
          <ReadMoreReact
            text={presentJob.requirements}
            min={60}
            ideal={100}
            max={300}
            readMoreText={
              <Link to="#" className="link-color">
                Read more &gt;
              </Link>
            }
          />
        </p>
      </div>

      {/* Job Qualifications */}
      {/* <div className="modal-div">
        <h5>Qualifications</h5>
        <p>
          <ReadMoreReact
            text={presentJob.qualifications}
            min={60}
            ideal={100}
            max={300}
            readMoreText={
              <Link to="#" className="link-color">
                Read more &gt;
              </Link>
            }
          />
        </p>
      </div> */}

      <div className="modal-div">
        <button onClick={() => openModal()} className="modalBtn">
          Apply
        </button>
        <Modal
          isOpen={modalIsOpen}
          onRequestClose={closeModal}
          style={customStyles}
          overlayClassName="modal-overlay"
          contentLabel="Example Modal"
          ariaHideApp={false}
        >
          <ModalComponentTwo closeModal={closeModal2} presentJob={presentJob} />
        </Modal>
      </div>
    </div>
  );
};

const ModalComponentTwo = ({ closeModal, presentJob }) => {
  return (
    <div className="modal-component__wrap">
      <div className="modal-title">
        <h3>Apply</h3>
        <span onClick={closeModal} style={{ cursor: "pointer" }}>
          <img src={closeModalIcon} alt="" />
        </span>
      </div>
      <p className="modal-text-two">Apply for the role of {presentJob.title}</p>
      <JobApplication applyBtnStyle={"modalBtn"} />
    </div>
  );
};
