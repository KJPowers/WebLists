package tld.domain;

import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.core.Application;

@ApplicationPath("") // Triggers JAX-RS and maps it to the root of ROOT.war
public class RestApplication extends Application
{

}
