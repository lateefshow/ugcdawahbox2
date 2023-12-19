                    
							<form  method="post" action="sessionmongo.php" autocomplete="on"> 
                                <h3>Log in</h3> <h3 style="color:#FF0000; font-size:30px"> Incorrect Email and Password </h3>
								<br>
								<h2 align="center" style="color:#FF0000"> Try and Login Again...! </h2>
								<hr>
								
								<hr />
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your Email </label>
                                    <input id="email" name="email" required="required" type="text"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your Password </label>
                                    <input id="password" name="password" required="required" type="password"/> 
                                </p>
                                <p class="login button"> 
                                    <input type="submit" name="login" value="Login" /> 
								</p>
                                <p class="change_link">
									Not a member yet ?
									<a href="#toregister" class="to_register">Join us</a>
								</p>
                            </form>