import React from "react";
import VideoItem from "./VideoItem";
import Container from "react-bootstrap/Container";
import ListGroup from "react-bootstrap/ListGroup";

const VideoList = ({ videos, onVideoSelect }) => {
  const renderedList = videos.map((video) => {
    return <VideoItem key={video.id.videoId} onVideoSelect={onVideoSelect} video={video} />;
  });

  return (
    <Container fluid="md">
      <ListGroup>{renderedList}</ListGroup>
    </Container>
  );
};

export default VideoList;
