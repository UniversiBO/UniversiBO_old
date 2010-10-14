/**
 *
 */
package util;

import java.lang.reflect.Field;
import java.util.Enumeration;

/**
 * Obtain line number information for any JTB node.  Note that
 * "any" really means any -- we use reflection to overcome the
 * problem that we do not know the full name of the AST root
 * (in particular, there maybe multiple AST hierarchies in the
 * project with multiple root "Node" classes).
 *
 * Essentially, pass a JTB node to the static "get" method
 * and (if it is a JTB node and there is source corresponding
 * to it), a LineNumberInfo object with the scope of the
 * subtree is returned.  Otherwise null.
 *
 *
 * Minimal example:<br><br>
 * <code>
 * void semanticError(String message, Node n) {<br>
 * &nbsp;&nbsp;&nbsp;  throw new Error(message + " at " + LineNumberInfo.get(n));<br>
 * }<br>
 * </code>

 * @author Christian Grothoff
 */
public class LineNumberInfo {

    public final int lineStart;
    public final int lineEnd;
    public final int colStart;
    public final int colEnd;

    private LineNumberInfo(int s, int e, int cs, int ce) {
        this.lineStart = s;
        this.lineEnd = e;
        this.colStart = cs;
        this.colEnd = ce;
    }

    public String toString() {
        return "["+lineStart+":"+colStart+","+lineEnd+":"+colEnd+"]";
    }

    public String desc()
    {
    	return "[line: "+lineStart+" - column: "+colStart+"]";
    }

    /**
     * Compute line number information for the given JTB node.
     *
     * @param o any JTB node (the type should be node, but since this
     *  code handles any JTB AST we cannot hardwire the
     *  type ("Node" -- but which package?)
     * @return
     */
    public static LineNumberInfo get(Object o) {
        if (o == null)
            return null; // fail!
        Object p = firstMatch(o);
        Object q = lastMatch(o);
        try {
            int lstart = ((Integer)p.getClass().getField("beginLine").get(p)).intValue();
            int cstart = ((Integer)p.getClass().getField("beginColumn").get(p)).intValue();
            int lend = ((Integer)p.getClass().getField("endLine").get(q)).intValue();
            int cend = ((Integer)p.getClass().getField("endColumn").get(q)).intValue();
            return new LineNumberInfo(lstart, lend, cstart, cend);
        } catch (Throwable t) {
            return null; // failed
        }
    }

    private static Object firstMatch(Object o) {
        if (o == null)
            return null;
        Class c = o.getClass();
        if  (c.getName().endsWith("NodeToken"))
            return o;
        try {
            int i=0;
            while (true) {
                Field f = null;
                try {
                    f = c.getField("f" + i);
                } catch (Throwable t) {
                }
                if ( (f == null) && (i == 0) && (c.getName().endsWith("NodeChoice")) ) {
                    f = c.getField("choice");
                } else if ( (f == null) && (i == 0) ) {
                    // special cases: node sequence, etc.
                    Enumeration e = (Enumeration) c.getMethod("elements").invoke(o);
                    while (e.hasMoreElements()) {
                        Object x = firstMatch(e.nextElement());
                        if (x != null)
                            return x;
                    }
                }
                if (f != null) {
                    Object r = firstMatch(f.get(o));
                    if (r != null)
                        return r;
                } else {
                    return null;
                }
                i++;
            }
        } catch (Throwable t) {
        }
        return null;
    }

    private static Object lastMatch(Object o) {
        if (o == null)
            return null;
        Class c = o.getClass();
        if  (c.getName().endsWith("NodeToken"))
            return o;

        Object ret = null;
        try {
            int i=0;
            while (true) {
                Field f = null;
                try {
                    f = c.getField("f" + i);
                } catch (Throwable t) {
                }
                if ( (f == null) && (i == 0) && (c.getName().endsWith("NodeChoice")) ) {
                    f = c.getField("choice");
                } else if ( (f == null) && (i == 0) ) {
                    // special cases: node sequence, etc.
                    Enumeration e = (Enumeration) o.getClass().getMethod("elements").invoke(o);
                    while (e.hasMoreElements()) {
                        Object x = lastMatch(e.nextElement());
                        if (x != null)
                            ret = x;
                    }
                    return ret;
                }
                if (f != null) {
                    Object r = lastMatch(f.get(o));
                    if (r != null)
                        ret = r;
                } else {
                    return ret;
                }
                i++;
            }
        } catch (Throwable t) {
        }
        return ret;
    }

}
