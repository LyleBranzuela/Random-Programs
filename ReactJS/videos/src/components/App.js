import React from "react";
import SearchBar from "./SearchBar";
import youtube from "../apis/youtube";
import VideoList from "./VideoList";
import VideoDetail from "./VideoDetail";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

class App extends React.Component {
  state = { videos: [], selectedVideo: null };

  componentDidMount() {
    this.onTermSubmit("buildings");
  }

  onTermSubmit = async (term) => {
    const KEY = "AIzaSyBWppm-cDLTr9tTN4rQ0UHgLfuLpex6iM0";
    const response = await youtube.get("/search", {
      params: {
        part: "snippet",
        maxResults: 5,
        type: "video",
        key: KEY,
        q: term,
      },
    });
    this.setState({
      videos: response.data.items,
      selectedVideo: response.data.items[0],
    });
  };

  onVideoSelect = (video) => {
    this.setState({ selectedVideo: video });
  };

  render() {
    return (
      <Container fluid="lg" style={{ marginTop: "50px" }}>
        <Row>
          <SearchBar onFormSubmit={this.onTermSubmit} />
        </Row>
        <Row>
          <Col xs={7}>
            <VideoDetail video={this.state.selectedVideo} />
          </Col>
          <Col xs={5}>
            <VideoList
              onVideoSelect={this.onVideoSelect}
              videos={this.state.videos}
            />
          </Col>
        </Row>
      </Container>
    );
  }
}

export default App;
