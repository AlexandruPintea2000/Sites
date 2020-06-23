import React from 'react';
import './Reacts.css';

class User extends React.Component
{
	constructor( props ) 
	{
		super( props );

		this.state = {
			username: props.username,
			password: props.password
		};

	}

	render ()
	{
		return (
			
			<tr>
				<td class="user">
					{ this.state.username }
				</td>
				<td class="user">
					{ this.state.password }
				</td>
			</tr>
		);
	}
}

function Application () {
  return (
    <div className="Application">



	<h2> Users </h2>

	<div class="div_center">
		<table>
			<User username="username" password="password" />
			<User username="username1" password="password1" />
			<User username="username2" password="password2" />
			<User username="username3" password="password3" />
			<User username="username4" password="password4" />
		</table>
	</div>


    </div>
  );
}

export default Application;
