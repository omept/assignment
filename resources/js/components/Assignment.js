import ReactDOM from 'react-dom';
// import axios from "axios";

import React, { Component } from 'react';
import axios from 'axios';

class Assignment extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'base_url': window.location.origin,
            'user_id': '',
            'unlocked_achievements': [],
            'next_available_achievements': [],
            'current_badge': '',
            'next_badge': '',
            'remaining_to_unlock_next_badge': '',
            'fetching_error': false,
        }
        this.userStats = this.userStats.bind(this);
    }

    handleUserIdChange = (e) => {
        this.setState({ user_id: e.target.value });
        // this.userStats();
    }

    userStats = () => {
        if (!this.state.user_id) {
            return;
        }

        let baseUrl = `${this.state.base_url}/users/${this.state.user_id}/achievements`;
        this.setState({ fetching_error: false });

        axios.get(baseUrl)
            .then((response) => {
                let { unlocked_achievements,
                    next_available_achievements,
                    current_badge,
                    next_badge,
                    remaining_to_unlock_next_badge } = response.data;
                this.setState({ remaining_to_unlock_next_badge, next_badge, current_badge, next_available_achievements, unlocked_achievements });
            }).catch((error) => {
                this.setState({ fetching_error: true });
            });
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-4">
                        <div className="card">
                            <div className="card-header justify-content-center" style={{ display: 'flex' }}>
                                <input
                                    type="number"
                                    min={1}
                                    placeholder='enter a number'
                                    onChange={this.handleUserIdChange}
                                />
                                <button onClick={() => this.userStats()}>  show results </button></div>

                        </div>
                    </div>
                    {!this.state.fetching_error ?
                        <div className="row justify-content-center">
                            <div className="col-md-4 m-5">
                                <div className="card">
                                    <div className="card-header">Unlocked Achievements</div>
                                    <div className="card-body">
                                        {this.state.unlocked_achievements.map((entry, index) => (
                                            <h5 key={index}> {entry} </h5>
                                        ))} <br />
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-4 m-5">
                                <div className="card">
                                    <div className="card-header">Next Available Achievements</div>
                                    <div className="card-body">
                                        {this.state.next_available_achievements.map((entry, index) => (
                                            <h5 key={index}> {entry} </h5>
                                        ))} <br />
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-4 m-5">
                                <div className="card">
                                    <div className="card-header">Current Badge</div>
                                    <div className="card-body h5"> {this.state.current_badge}</div>
                                </div>
                            </div>
                            <div className="col-md-4 m-5">
                                <div className="card">
                                    <div className="card-header">Next Badge</div>
                                    <div className="card-body h5"> {this.state.next_badge}</div>
                                </div>
                            </div>
                            <div className="col-md-4 m-5">
                                <div className="card">
                                    <div className="card-header">Remaining To Unlock Next Badge</div>
                                    <div className="card-body h5"> {this.state.remaining_to_unlock_next_badge}</div>
                                </div>
                            </div>
                        </div> : <div className="row justify-content-center"> Error Occurred</div>}
                </div>
            </div>
        );
    }
}


export default Assignment;

if (document.getElementById('app')) {
    ReactDOM.render(<Assignment />, document.getElementById('app'));
}
