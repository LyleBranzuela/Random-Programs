import React from "react";
import Container from "react-bootstrap/Container";
import Form from "react-bootstrap/Form";

class SearchBar extends React.Component {
  state = { term: "" };

  onFormSubmit = (event) => {
    event.preventDefault();

    this.props.onSubmit(this.state.term);
  };

  render() {
    return (
      <Container fluid="md">
        <Form onSubmit={this.onFormSubmit}>
          <Form.Group>
            <Form.Label>Image Search</Form.Label>
            <Form.Control
              type="text"
              placeholder="Search Image"
              value={this.state.term}
              onChange={(e) => this.setState({ term: e.target.value })}
            />
            <Form.Text className="text-muted">
              Share a specific image.
            </Form.Text>
          </Form.Group>
        </Form>
      </Container>
    );
  }
}

export default SearchBar;
