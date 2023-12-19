                            <form  action="submit2.php" method="post" autocomplete="on"> 
                                <h4> Sign up </h4> 
								<hr>
                                
								<p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="u">Your First Name </label>
                                    <input id="passwordsignup" name="ftname" required="required" type="text" placeholder="First Name"/>
                                </p>
								<p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="u">Your Last Name </label>
                                    <input id="passwordsignup" name="ltname" required="required" type="text" placeholder="Last Name"/>
                                </p>
								<p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="">Your Gender </label>
									<select id="passwordsignup"  name="gender">
										<option>None</option>
										<option>Male</option>
										<option>Female</option>
									</select>
                                </p>
								
								<p> 
                                    <label for="usernamesignup" class="uname" data-icon="e">Your Email</label>
                                    <input id="usernamesignup" name="email" required="required" type="text" placeholder="Email" />
                                </p>
								<p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                                    <input id="usernamesignup" name="username" required="required" type="text" placeholder="Username" />
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                    <input id="passwordsignup" name="password" required="required" type="password" placeholder="Password"/>
                                </p>
								<p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Your Phone Number</label>
                                    <input id="usernamesignup" name="tel" required="required" type="text" placeholder="Phone Number" />
                                </p>
                                <p class="signin button"> 
									<input type="submit" value="Sign up"/> 
								</p>
                                <p class="change_link">  
									Already a member ?
									<a href="#tologin" class="to_register"> Go and log in </a>
								</p>
                            </form>