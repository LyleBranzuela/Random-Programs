import React from "react";
import Container from "react-bootstrap/Container";
import Form from "react-bootstrap/Form";

class SearchBar extends React.Component {
  state = { term: "" };

  onInputChange = (event) => {
    this.setState({ term: event.target.value });
  };

  onFormSubmit = (event) => {
    // Prevents the Form From Submitting Itself
    event.preventDefault();
    this.props.onFormSubmit(this.state.term);
  };

  render() {
    return (
      <Container fluid="md" className="search-bar">
        <Form onSubmit={this.onFormSubmit}>
          <Form.Group >
            <Form.Label>Video Search</Form.Label>
            <Form.Control
              type="text"
              value={this.state.term}
              onChange={this.onInputChange}
            />
          </Form.Group>
        </Form>
      </Container>
    );
  }
}

export default SearchBar;
