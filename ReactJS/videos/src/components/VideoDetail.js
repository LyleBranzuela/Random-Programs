import React from "react";
import Container from "react-bootstrap/Container";
import ResponsiveEmbed from "react-bootstrap/ResponsiveEmbed";
import Row from "react-bootstrap/Row";

const VideoDetail = ({ video }) => {
  if (!video) {
    return <Container>Loading...</Container>;
  } else {
    // const videoSrc = "https://www.youtube.com/embed/" + video.id.videoId;
    const videoSrc = `https://www.youtube.com/embed/${video.id.videoId}`;

    return (
      <Container style={{ width: "650px", height: "auto", marginTop: "15%" }}>
        <Row>
          <ResponsiveEmbed aspectRatio="16by9">
            <iframe src={videoSrc} />
          </ResponsiveEmbed>
        </Row>
        <Row style={{ marginTop: "5%" }}>
          <h4>{video.snippet.title}</h4>
          <p>{video.snippet.description}</p>
        </Row>
      </Container>
    );
  }
};

export default VideoDetail;
